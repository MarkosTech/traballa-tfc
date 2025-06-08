#!/bin/bash

# PHPUnit Test Runner Script for Traballa TFC
# Usage: ./run-tests.sh [options]

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Default options
COVERAGE=false
VERBOSE=false
FILTER=""
TESTSUITE=""

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Function to show help
show_help() {
    echo "Usage: $0 [OPTIONS]"
    echo ""
    echo "Options:"
    echo "  -h, --help           Show this help message"
    echo "  -c, --coverage       Generate code coverage report"
    echo "  -v, --verbose        Verbose output"
    echo "  -f, --filter FILTER  Run only tests matching filter"
    echo "  -s, --suite SUITE    Run specific test suite (Unit|Integration)"
    echo "  --unit               Run only unit tests"
    echo "  --integration        Run only integration tests"
    echo ""
    echo "Examples:"
    echo "  $0                   Run all tests"
    echo "  $0 --coverage        Run all tests with coverage"
    echo "  $0 --unit            Run only unit tests"
    echo "  $0 --filter Session  Run only tests matching 'Session'"
}

# Parse command line arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        -h|--help)
            show_help
            exit 0
            ;;
        -c|--coverage)
            COVERAGE=true
            shift
            ;;
        -v|--verbose)
            VERBOSE=true
            shift
            ;;
        -f|--filter)
            FILTER="$2"
            shift 2
            ;;
        -s|--suite)
            TESTSUITE="$2"
            shift 2
            ;;
        --unit)
            TESTSUITE="Unit Tests"
            shift
            ;;
        --integration)
            TESTSUITE="Integration Tests"
            shift
            ;;
        *)
            print_error "Unknown option: $1"
            show_help
            exit 1
            ;;
    esac
done

# Check if we're in the right directory
if [ ! -f "phpunit.xml" ]; then
    print_error "phpunit.xml not found. Please run this script from the project root."
    exit 1
fi

# Check if PHPUnit is installed
if [ ! -f "vendor/bin/phpunit" ]; then
    print_error "PHPUnit not found. Please run 'composer install' first."
    exit 1
fi

print_status "Starting PHPUnit tests..."

# Build PHPUnit command
PHPUNIT_CMD="vendor/bin/phpunit"

# Add configuration file
PHPUNIT_CMD="$PHPUNIT_CMD --configuration phpunit.xml"

# Add verbose flag if requested
if [ "$VERBOSE" = true ]; then
    PHPUNIT_CMD="$PHPUNIT_CMD --verbose"
fi

# Add coverage flag if requested
if [ "$COVERAGE" = true ]; then
    print_status "Code coverage reporting enabled"
    PHPUNIT_CMD="$PHPUNIT_CMD --coverage-html tests/coverage --coverage-text"
fi

# Add filter if specified
if [ ! -z "$FILTER" ]; then
    print_status "Running tests matching filter: $FILTER"
    PHPUNIT_CMD="$PHPUNIT_CMD --filter $FILTER"
fi

# Add test suite if specified
if [ ! -z "$TESTSUITE" ]; then
    print_status "Running test suite: $TESTSUITE"
    PHPUNIT_CMD="$PHPUNIT_CMD --testsuite \"$TESTSUITE\""
fi

print_status "Command: $PHPUNIT_CMD"
echo ""

# Run PHPUnit
if eval $PHPUNIT_CMD; then
    print_success "All tests passed!"
    
    if [ "$COVERAGE" = true ]; then
        print_status "Coverage report generated in tests/coverage/"
        if command -v xdg-open &> /dev/null; then
            print_status "Opening coverage report..."
            xdg-open tests/coverage/index.html &
        fi
    fi
else
    print_error "Some tests failed!"
    exit 1
fi
