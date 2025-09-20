/**
 * The Forgotten Server - a free and open-source MMORPG server emulator
 * Copyright (C) 2019  Mark Samman <mark.samman@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

#include "otpch.h"

#include "configmanager.h"
#include "database.h"

#include <mysql.h>

extern ConfigManager g_config;

Database::Database()
{
	handle = mysql_init(nullptr);
	if (!handle) {
		std::cout << std::endl << "Failed to initialize MySQL connection handler." << std::endl;
		return;
	}
}

Database::~Database()
{
	if (handle) {
		mysql_close(handle);
	}
}

bool Database::connect()
{
	// Automatic reconnect is enabled for MySQL versions below 5.0.19
	// if any of the MySQL servers has a version >= 5.0.19 this option
	// has to be enabled manually
	bool reconnect = true;
	mysql_options(handle, MYSQL_OPT_RECONNECT, &reconnect);

	if (!mysql_real_connect(handle, g_config.getString(ConfigManager::MYSQL_HOST).c_str(),
		g_config.getString(ConfigManager::MYSQL_USER).c_str(), g_config.getString(ConfigManager::MYSQL_PASS).c_str(), g_config.getString(ConfigManager::MYSQL_DB).c_str(), g_config.getNumber(ConfigManager::SQL_PORT),
		g_config.getString(ConfigManager::MYSQL_SOCK).c_str(), 0)) {
		std::cout << "Failed to connect to database. MYSQL ERROR: " << mysql_error(handle) << std::endl;
		return false;
	}

	DBResult_ptr result = storeQuery("SHOW VARIABLES LIKE 'max_allowed_packet'");
	if (result) {
		if (result->getNumber<uint64_t>("Value") < 16777216) {
			std::cout << std::endl << "Warning: max_allowed_packet might be set too low for binary map loading. Set it to at least 16M." << std::endl;
			std::cout << "If you get a lot of 'WARNING: Retry to send ...' messages while loading the map increase max_allowed_packet." << std::endl;
		}
	}
	return true;
}

bool Database::beginTransaction()
{
	if (!executeQuery("BEGIN")) {
		return false;
	}

	databaseLock.lock();
	return true;
}

bool Database::rollback()
{
	if (!handle) {
		std::cout << "[Error - mysql::rollback] Connection handle is NULL" << std::endl;
		return false;
	}

	if (mysql_rollback(handle) != 0) {
		std::cout << "[Error - mysql::rollback] " << mysql_error(handle) << std::endl;
		databaseLock.unlock();
		return false;
	}

	databaseLock.unlock();
	return true;
}

bool Database::commit()
{
	if (!handle) {
		std::cout << "[Error - mysql::commit] Connection handle is NULL" << std::endl;
		return false;
	}

	if (mysql_commit(handle) != 0) {
		std::cout << "[Error - mysql::commit] " << mysql_error(handle) << std::endl;
		databaseLock.unlock();
		return false;
	}

	databaseLock.unlock();
	return true;
}

bool Database::executeQuery(const std::string& query)
{
	if (!handle) {
		std::cout << "[Error - mysql::executeQuery] Connection handle is NULL" << std::endl;
		return false;
	}

	bool success = true;

	// executes the query
	databaseLock.lock();

	while (mysql_real_query(handle, query.c_str(), query.length()) != 0) {
		std::cout << "[Error - mysql::executeQuery] Query failed: " << mysql_error(handle) << std::endl;
		std::cout << "Query: " << query << std::endl;
		if (mysql_errno(handle) != CR_SERVER_LOST) {
			success = false;
			break;
		}
		std::cout << "[Warning - mysql::executeQuery] Reconnecting to database..." << std::endl;
		mysql_close(handle);
		if (!connect()) {
			success = false;
			break;
		}
	}

	MYSQL_RES* m_res = mysql_store_result(handle);
	databaseLock.unlock();

	if (m_res) {
		mysql_free_result(m_res);
	}

	return success;
}

DBResult_ptr Database::storeQuery(const std::string& query)
{
	if (!handle) {
		std::cout << "[Error - mysql::storeQuery] Connection handle is NULL" << std::endl;
		return nullptr;
	}

	databaseLock.lock();

retry:
	while (mysql_real_query(handle, query.c_str(), query.length()) != 0) {
		std::cout << "[Error - mysql::storeQuery] Query failed: " << mysql_error(handle) << std::endl;
		std::cout << "Query: " << query << std::endl;
		if (mysql_errno(handle) != CR_SERVER_LOST) {
			break;
		}
		std::cout << "[Warning - mysql::storeQuery] Reconnecting to database..." << std::endl;
		mysql_close(handle);
		if (!connect()) {
			break;
		}
	}

	// we should call that every time as someone would call executeQuery('SELECT...')
	// as it is described in MySQL manual: "it doesn't hurt" :P
	MYSQL_RES* res = mysql_store_result(handle);
	if (!res) {
		std::cout << "[Error - mysql::storeQuery] Missing result set" << std::endl;
		std::cout << "Query: " << query << std::endl;
		if (mysql_errno(handle) == CR_SERVER_LOST) {
			std::cout << "[Warning - mysql::storeQuery] Reconnecting to database..." << std::endl;
			mysql_close(handle);
			if (connect()) {
				goto retry;
			}
		}
		databaseLock.unlock();
		return nullptr;
	}

	// retrieving results of query
	DBResult_ptr result = std::make_shared<DBResult>(res);
	if (!result->hasNext()) {
		databaseLock.unlock();
		return nullptr;
	}
	databaseLock.unlock();
	return result;
}

std::string Database::escapeString(const std::string& s) const
{
	return escapeBlob(s.c_str(), s.length());
}

std::string Database::escapeBlob(const char* s, uint32_t length) const
{
	// the worst case is 2n + 1
	size_t maxLength = (length * 2) + 1;

	std::string escaped;
	escaped.reserve(maxLength + 2);
	escaped.push_back('\'');

	if (length != 0) {
		char* output = new char[maxLength];
		mysql_real_escape_string(handle, output, s, length);
		escaped.append(output);
		delete[] output;
	}

	escaped.push_back('\'');
	return escaped;
}

DBResult::DBResult(MYSQL_RES* res)
{
	handle = res;

	size_t i = 0;

	MYSQL_FIELD* field = mysql_fetch_field(handle);
	while (field) {
		listNames[field->name] = i++;
		field = mysql_fetch_field(handle);
	}

	row = mysql_fetch_row(handle);
}

DBResult::~DBResult()
{
	mysql_free_result(handle);
}

std::string DBResult::getString(const std::string& s) const
{
	auto it = listNames.find(s);
	if (it == listNames.end()) {
		std::cout << "[Error - DBResult::getString] Column '" << s << "' does not exist in result set." << std::endl;
		return std::string();
	}

	if (row[it->second] == nullptr) {
		return std::string();
	}

	return std::string(row[it->second]);
}

const char* DBResult::getStream(const std::string& s, unsigned long& size) const
{
	auto it = listNames.find(s);
	if (it == listNames.end()) {
		std::cout << "[Error - DBResult::getStream] Column '" << s << "' does not exist in result set." << std::endl;
		size = 0;
		return nullptr;
	}

	if (row[it->second] == nullptr) {
		size = 0;
		return nullptr;
	}

	size = mysql_fetch_lengths(handle)[it->second];
	return row[it->second];
}

bool DBResult::hasNext() const
{
	return row != nullptr;
}

bool DBResult::next()
{
	row = mysql_fetch_row(handle);
	return row != nullptr;
}