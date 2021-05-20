# COVID-19 Vaccination Slot Booking Script

### Important: 
- This is a proof of concept project. I do NOT endorse or condone, in any shape or form, automating any monitoring/booking tasks. **Use at your own risk.**
- This CANNOT book slots automatically. It doesn't skip any of the steps that a normal user would have to take on the official portal. You will still have to enter the OTP and Captcha.
- Do NOT use unless all the beneficiaries selected are supposed to get the same vaccine and dose. 
- There is no option to register new mobile or add beneficiaries. This can be used only after beneficiary has been added through the official app/site.
- API Details (read the first paragraph at least): https://apisetu.gov.in/public/marketplace/api/cowin/cowin-public-v2

### Usage:

Used Terminal Js Library https://terminal.jcubic.pl/

Run index.html file and terminal like window open in browser

Type start to run script and all instruction are mention in the script 

### Improvement Needed:
- Some time center stuck in https://cdn-api.co-vin.in/api/v2/appointment/sessions/calendarByDistrict & http://cdn-api.co-vin.in/api/v2/appointment/sessions/calendarByPin api even though the all slot is booked. So some sort of filter needed for this stucked center.