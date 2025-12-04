# Changelog

All notable changes to `mpesa-php-sdk` will be documented in this file

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
## [2.2.0] - 2025-12-05
### Added
- **Laravel Integration**: Added Laravel integration with service provider and facade

## [2.0.0] - 2025-01-17
### Added
- **Token Management System**: Intelligent token management with caching
  - `TokenManager` class with automatic token generation and caching
  - Smart token reuse to improve performance
  - Manual token clearing capabilities
- **Constants System**: Comprehensive constants for API responses and transaction status
  - `ResponseCodes` class with all M-Pesa API response codes
  - `TransactionStatus` class with transaction status constants
  - Utility methods for status checking (`isCompleted()`, `isPending()`, `isFailed()`)
- **Parameter Validation**: Robust input validation system
  - `ParameterValidator` class with comprehensive validation rules
  - MSISDN validation for Mozambique phone numbers
  - Transaction reference and service provider code validation
  - Amount validation with proper type checking
- **Exception System**: Custom exception hierarchy for better error handling
  - `MpesaException` base exception with context support
  - `ValidationException` for parameter validation errors
  - `AuthenticationException` for token/auth failures
  - `ApiException` for API-specific errors with response codes
- **B2B Transactions**: Complete Business-to-Business transaction support
  - `b2b()` method for B2B payments
  - Synchronous and asynchronous B2B response classes
  - B2B response factory for automatic response creation
- **Enhanced Response Classes**: Improved response handling across all transaction types
  - Better error detection with `isApiSuccess()` method
  - Consistent response parsing and data access
  - Integration with new constants system
- **Comprehensive Testing**: Full test suite for reliability
  - Unit tests for all constants and validation logic
  - Token manager testing with expiration scenarios
  - Parameter validation testing for all transaction types
  - PHPUnit configuration with coverage reporting

### Enhanced
- **Main SDK Class**: Updated `Mpesa` class with validation and improved error handling
  - Automatic parameter validation on all transaction methods
  - Integration with new token management system
  - Better exception handling with specific error types
- **Documentation**: Completely updated README.md with comprehensive examples
  - Token management usage examples
  - Error handling patterns
  - Parameter validation examples
  - Best practices for token clearing and regeneration
- **Project Structure**: Organized codebase with clear separation of concerns
  - `Auth/` directory for authentication components
  - `Constants/` directory for API constants
  - `Exceptions/` directory for custom exceptions
  - `Validation/` directory for input validation
  - Improved response class organization

### Changed
- **Breaking Changes**: Updated method signatures and response handling
  - Token generation now uses `TokenManager` instead of direct generation
  - Removed `getData()` method from response classes - use specific getters instead
  - Response classes now include additional validation methods
  - Exception handling requires catching specific exception types
- **Response Architecture**: Refactored response classes for better maintainability
  - Created `AsyncResponse` and `SyncResponse` base classes
  - Eliminated ~90% of code duplication between similar response classes
  - Improved consistency across all response types
- **Performance**: Improved performance through token caching and reuse
- **Reliability**: Enhanced error handling and input validation
- **Maintainability**: Better code organization and comprehensive testing

### Examples
- **Complete Example**: Added comprehensive usage example (`examples/complete_example.php`)
- **Token Management Example**: Dedicated token management demonstration
- **Best Practices**: Documented patterns for production usage
## [1.4.0] - 2020-11-06
### Added
-  laravel compatibility
-  ServiceProviderCode Parameter initialization
### Changed
- ServiceProvider Optional on transactions methods
## [1.3.0] - 2020-11-06
- Update dependencies
## [1.2.2] - 2020-05-22
### Changed
- Disable SSL Verification behavior of request

## [1.0] - 2020-01-14
### Added
- Readme
