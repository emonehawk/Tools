# Marketing Mail Subject Generator

## Description

This project is a tool designed to generate compelling subject lines for marketing emails. It uses a combination of chatGPT API and predefined templates to create unique and engaging subjects. The project consists of two main files: `generator.php` which is responsible for the generation logic, and `testGenerator.php` which contains unit tests for the generator. This project uses gpt-3.5-turbo-0125.

## Requirements

- PHP 7.4 or higher
- Composer
- chatGPT API

## Installation

To install this project, follow these steps:

1. Clone the repository: `git clone https://github.com/emonehawk/tools.git`.
2. Navigate to the project directory: `cd marketing-mail-subject-generator`.
3. Install PHP if not already installed. You can check this by running `php -v` in your terminal. If PHP is not installed, you can install it by following the instructions [here](https://www.php.net/manual/en/install.php).
4. Install Composer if not already installed. You can check this by running `composer --version` in your terminal. If Composer is not installed, you can install it by following the instructions [here](https://getcomposer.org/download/).
5. Install the required dependencies: `composer install`.

After running `composer install`, a `vendor` directory will be created. This directory contains all the project dependencies and the autoloader script. It should not be deleted or manually altered.

## Usage

To use this tool, run the following command in the terminal:

```bash
php generator.php "Your input here"
