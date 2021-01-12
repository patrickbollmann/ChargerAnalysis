from bs4 import BeautifulSoup
from selenium.webdriver.chrome.options import Options
from selenium import webdriver
import time
import datetime
import database
while True:
    db = database.DataBase()
    chargers=db.query("SELECT Name, Url FROM chargerToAnalyze")

    chrome_options = Options()
    chrome_options.add_argument("--headless")
    driver = webdriver.Chrome(chrome_options=chrome_options)

    t = datetime.datetime.now()
    m=t.minute
    h= "{0:0=2d}".format(t.hour)
    m=m-(m%2)
    m = "{0:0=2d}".format(m)
    tm = str(h)+":"+str(m)

    delay=0
    for i in chargers:
        try:
            delay+=6
            url=i["Url"]
            name=i["Name"]

            driver.get(url)
            time.sleep(5)
            text=driver.page_source

            soup = BeautifulSoup(text, 'html.parser')
            result = str(soup.find("div", {"class": "page-contents--chargeport-availability"}).text)
            status=1
            if(result=="Verfügbar"):
                status = 0
            else:
                status = 1

            db.query("INSERT INTO analyse (Time, Status, Url) VALUES('"+tm+"', '"+str(status)+"', '"+url+"')")

            print("Status of Charger "+name+" is: "+result)
        except Exception as e:
            print(e)
    print("waiting 2 Minutes until next check")
    driver.close()
    wait=119-delay
    time.sleep(wait)
