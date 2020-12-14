# This is not my work.  I am running this to compare my version in PHP to this.

def tax_calculator():
    #get the total monthly sales input from user.
    total_sales = float(input('Please enter the total monthly sales:'))
    state_tax = 0.08 * total_sales
    county_tax = 0.035 * total_sales
    total_tax = state_tax + county_tax

    #display the amounts of sales tax.
    print('County sales tax:', county_tax)
    print('State sales tax:', state_tax)
    print('Total sales tax:', total_tax)

tax_calculator()
