=== CBX Accounting ===
Contributors: manchumahara,codeboxr
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=NWVPKSXP6TCDS
Tags: accounting, bookkeeping, book keeping, income, expense log
Requires at least: 3.5
Tested up to: 4.7
Stable tag: 1.2.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Accounting solution SME business inside wordpress

== Description ==

CBX Accounting plugin gives easy and quick solution for book keeping for small business inside wordpress to keep the
income and expense log.

Learn more http://codeboxr.com/product/cbx-accounting/

Features of the plugin include:

* Current year expense and income overview graph
* Add Expense or Income
* Assign expense or income to category
* Category manager for income and expense
* Different category for income and expense
* Overview of total income & expense(year & month) via chart, this month total income & expense, latest total income & expense
* More feature via pro & free plugins


**Languages**

CBX Accounting has been translated into the following languages:

1. British English


Pro addons available, learn more http://codeboxr.com/product/cbx-accounting/

Pro Addons:

* Log manager http://codeboxr.com/product/log-manager-addon-for-cbx-accounting
* Statement addon http://codeboxr.com/product/statement-addon-for-cbx-accounting

== Installation ==

1. Upload `cbxwpsimpleaccounting.zip` to the `/wp-content/plugins/` directory or search in wordpress dir from wordpress for "CBX Accounting"
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You'll now see a menu called "CBX Accounting" in left menu of word, start from there


== Screenshots ==

1. Admin - Overview  - Graph
2. Admin - Overview  - Quick Summary
3. Admin - Overview  - Latest Income
4. Admin - Overview  - Latest Expense
5. Admin - Category Manager
6. Admin - Income/Expense Add, Edit
7. Admin - Setting 1
8. Admin - Setting 2
9. Admin - Currently available addons from wpboxr.com

== Changelog ==
= 1.2.5 =
* [New] Account Manager (Bank account, cash etc)
* [Improvement] Dropped jQueryUI datepicker, added lightweight flatpickr
* [New] New free addon plugin support "CBX Accounting Vendors & Clients"

= 1.2.4 =
* Fixed decimal input problem for tax in income/expense entry
* Fixed decimal input problem for tax in setting
* New Currency Iranian rial added

= 1.2.3 =
* removed deprecated php function key_exists() and replaced with array_key_exists()
* Fixed Quick summary in overview page(total income and total expense was showing wrong or from latest 20)
* Quick summary in overview page in tabular format with profit/loss
* new method in admin facing class format_value_quick($value) to show any value as formatted
* three new method in admin facing class get_data_month_total_quick($year = 0, $month = 0, $format = false), get_data_year_total_quick($year = 0, $format = false) and get_data_total_quick($format = false)


= 1.2.2 =
* Updated type fields in adding income/expense,category and account manager from selectbox to radio
* Updated income/expense data entry by adding datetime field for adding retrospective data
* Updated tax value support for expense entries
* In overview page added month chart
* In overview page added next-previous traversal of year and month chart
* Fixed bug for active settings page selection when redirected form plugin listing
* Add/Edit category now color input text field is visible
* On Every entry the add_date is now of type 'datetime' instead of 'timestamp'
* Some spelling updated and fixed
= 1.2.1 =
* Updated the income/expense Amount and Source Amount field to take float value upto 2 decimal point
= 1.2.0 =
* Category color (New feature added)
* Account Manager (New feature added)
* Role based access (New feature added)
* Tax(vat) (New feature added)
* Charge display is now powered by google charts
* Overall improvement


= 1.0.6 =
* Initial public release

