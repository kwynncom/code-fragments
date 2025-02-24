# Written by Grok 3 Beta

# Need a server running:
# soffice --headless --accept="socket,host=localhost,port=2002;urp;"

import uno
from com.sun.star.beans import PropertyValue
import os

# Configuration
input_file = "file:///var/kwynn/lawHours.ods"  # Absolute path to your .ods file
output_dir = "/tmp/t/"  # Where you want CSVs saved

# Ensure output directory exists
os.makedirs(output_dir, exist_ok=True)

# Connect to LibreOffice
local_context = uno.getComponentContext()
resolver = local_context.ServiceManager.createInstanceWithContext(
    "com.sun.star.bridge.UnoUrlResolver", local_context)
context = resolver.resolve("uno:socket,host=localhost,port=2002;urp;StarOffice.ComponentContext")
desktop = context.ServiceManager.createInstanceWithContext("com.sun.star.frame.Desktop", context)

# Load the spreadsheet
properties = (PropertyValue("Hidden", 0, True, 0),)
document = desktop.loadComponentFromURL(input_file, "_blank", 0, properties)

# Get all sheets
sheets = document.getSheets()
sheet_count = sheets.getCount()

# Extract and save each sheet
for i in range(sheet_count):
    sheet = sheets.getByIndex(i)
    sheet_name = sheets.getElementNames()[i]
    output_file = os.path.join(output_dir, f"{sheet_name}.csv")

    # Get used range
    cursor = sheet.createCursor()
    cursor.gotoEndOfUsedArea(False)
    used_range = cursor.getRangeAddress()
    rows = used_range.EndRow + 1
    cols = used_range.EndColumn + 1
    print(f"Sheet '{sheet_name}': {rows} rows, {cols} columns")

    # Write to CSV
    with open(output_file, "w") as csvfile:
        for row in range(rows):
            row_data = []
            for col in range(cols):
                try:
                    cell = sheet.getCellByPosition(col, row)
                    value = cell.getString() or str(cell.getValue())
                    if "," in value or '"' in value:
                        value = f'"{value.replace("\"", "\"\"")}"'
                    row_data.append(value)
                except Exception as e:
                    print(f"Failed at sheet '{sheet_name}', row {row}, col {col}: {e}")
                    raise
            csvfile.write(",".join(row_data) + "\n")

# Close the document
document.close(True)
print(f"Exported {sheet_count} sheets to {output_dir}")