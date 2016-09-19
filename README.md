# QuickDashboard

The QuickDashboard is a custom company dashboard for internal purposes only. The dashboard visualizes key sales, cost and balance figures in simple tables and charts. The purpose is to give an overview of the most important figures for the management.

# Server Requirements

The server requirements are as follows:

1. 256MB RAM
2. 50MB free hard drive space
3. PHP >= 7.0
4. Apache2

## Required PHP modules & drivers

1. SQLSRV 4.0

# Setup

## Installation

1. Download the QuickDashboard content to your web directory (e.g. /var/www/html/QuickDashboard). 
2. Download the [Models](https://github.com/Orange-Management/Model) from Orange-Management into the same directory as the content of the QuckDashboard (e.g. /var/www/html/QuickDashboard).
3. Download the [jsOMS](https://github.com/Orange-Management/jsOMS) framework from Orange-Management into the same directory as the content of the QuckDashboard (e.g. /var/www/html/QuickDashboard).
4. Enable and allow mod_rewrite.

## Configuration

1. Set the connection credentials for the database.
2. Set the page root if the QuickDashboard is located in a subdirectory of your web directeory (e.g. /QuickDashboard/).
3. Define if this application should use https.
4. Set the start month of the fiscal year.
