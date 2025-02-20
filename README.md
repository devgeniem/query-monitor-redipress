# Query Monitor - RediPress Extension

A WordPress plugin that extends Query Monitor to provide insights into RediPress queries.

## Features

- Shows RediPress queries, parameters and number of results found
- Monitors RediPress query times
- Integrates seamlessly with Query Monitor's interface

## Requirements

- Query Monitor plugin
- RediPress plugin

## Installation

1. Install via Composer:
```bash
composer require hiondigital/query-monitor-redipress
```

2. Activate the plugin through WordPress admin interface.

## Usage

Once activated, the plugin adds a new "RediPress" panel to Query Monitor. This panel shows:

- Raw queries
- Number of query results
- Params passed to each query
- Time elapsed

The data is collected only on front-end requests.

## Development

### Building from Source

1. Clone the repository
2. Install dependencies:
```bash
composer install
```

## Credits

Created and maintained by [Hion Digital](https://www.hiondigital.com/).
