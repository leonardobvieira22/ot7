find_path(CRYPTOPP_INCLUDE_DIR cryptopp/config.h
    /usr/local/include
    /usr/include
)

find_library(CRYPTOPP_LIBRARY NAMES cryptopp
    PATHS
    /usr/local/lib
    /usr/lib
)

if(CRYPTOPP_INCLUDE_DIR AND CRYPTOPP_LIBRARY)
    set(CRYPTOPP_FOUND TRUE)
    set(Crypto++_FOUND TRUE)
    set(CRYPTOPP_INCLUDE_DIRS ${CRYPTOPP_INCLUDE_DIR})
    set(CRYPTOPP_LIBRARIES ${CRYPTOPP_LIBRARY})
endif()

mark_as_advanced(CRYPTOPP_INCLUDE_DIR CRYPTOPP_LIBRARY)
