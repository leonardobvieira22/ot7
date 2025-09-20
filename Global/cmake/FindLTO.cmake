include(CheckCXXCompilerFlag)

check_cxx_compiler_flag(-flto COMPILER_SUPPORTS_LTO)
if(COMPILER_SUPPORTS_LTO)
    set(LTO_FOUND TRUE)
    set(LTO_FLAGS "-flto")
else()
    set(LTO_FOUND FALSE)
    set(LTO_FLAGS "")
endif()

mark_as_advanced(LTO_FOUND LTO_FLAGS)
