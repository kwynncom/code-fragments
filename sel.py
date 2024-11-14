# sudo apt install python3-selenium - Ubuntu 24.04 Noble Numbat
# Firefox is installed with snap, and it appears that the geckodriver is installed with Firefox by default as below

from selenium import webdriver
service = webdriver.FirefoxService(executable_path='/snap/bin/firefox.geckodriver')
driver = webdriver.Firefox(service=service)
driver.get('https://kwynn.com?from=SeleniumExample_2024_11&at=14-0039')
