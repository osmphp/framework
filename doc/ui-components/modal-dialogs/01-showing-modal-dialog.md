# How-To: Showing Modal Dialog #

Modal dialog is shown with

    dialogs.show(template, variables)

Template specifies unique dialog template identifier, more on that below.

Variables is JS object with variable names in keys and passed variable values in values. At the very minimum there should be 2 variables, `width` and `height`.

This function returns result of dialog:

* for yes/no dialog it returns `true/false`.
* for confirmation dialog, it returns `undefined`.
* for selection dialog, it returns selected ids or filter condition which when applied returns selected records.

