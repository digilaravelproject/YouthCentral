1. @http://127.0.0.1:8000/directory replace this link with @http://127.0.0.1:8000/categories 
2. on @http://127.0.0.1:8000/vendor/businesses  when vendor clicks on delete listing add a confirm method. not done kindly recheck
3. http://127.0.0.1:8000/vendor/businesses when clicked on view in directory -> it shall open the business in the public page.
4. http://127.0.0.1:8000/vendor/dashboard -> there is no logout option - add it in the sidebar. 
5. @http://127.0.0.1:8000/my-events if event is in pending/failed stage and when the user or vendor clicks on the view public page it shall show a toast (for pending - yellow, failed in red color) with respective messages (pending- the event is under review & for failed -> your event is been reject by the admin )
6. if the vendors clicks on the claim this business in the business details page( for example http://127.0.0.1:8000/business/506635 he shall be redirected to http://127.0.0.1:8000/vendor/claim-business/506635) but if the user clicks on claim this business he shall see the the existing popup 
7. remove the view vendor profile from - http://127.0.0.1:8000/admin/claims/8 -> instead vendor information (which is shown rightnow) and add option to showcase - all the exisiting business name registered or claimed by the vendor with option to view the business (this will open the business public page) & edit - in a table format
8. http://127.0.0.1:8000/ in mobile veiw - in the hero search section - the background cointainer height shall dynamically reduce and increase as per the subcategory is expanded or collapsed
9. while creating a plan and one time option is selected -> on http://127.0.0.1:8000/admin/plans/create -> duration field become disabled -> when i click on create plan it shows "The duration value field is required." this error -> expected behaviour - when one time option is selected the plan will be valid for unlimited period -> rest of the option are correct and work like they are working right now
10. remove fas fa-search from the http://127.0.0.1:8000/admin/dashboard, http://127.0.0.1:8000/dashboard, http://127.0.0.1:8000/vendor/dashboard - from the navbar in the dashboard only
11. in http://127.0.0.1:8000/business/175566 -> quick-menu option has stopped working -> expected outcome -> when clicked icon-focus gal - it shall scroll to gallery option on the same page and so on -> donot make any changes to the ui only add the id/class to it so it scroll to particular section on the page
12. http://127.0.0.1:8000/vendor/my-claims getting this error "Attempt to read property "business_name" on null" on this page 



