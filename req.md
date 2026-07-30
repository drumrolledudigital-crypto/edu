IMPORTANT: Implement the following three requested changes without changing the existing project architecture or redesigning the UI.



Follow the current coding standards, business logic, AJAX flow, and design system.



==================================================

CHANGE #1

Student Registration Welcome Message

==================================================



Current:



After successful student registration, the welcome message shows:



"Welcome to Drumroll"



Required:



Change it to:



"Welcome to Drumroll Edu"



Update this everywhere consistently:



\- Registration Success Message

\- Welcome Email

\- Dashboard Welcome Text

\- Toast Notifications

\- Any other location where "Drumroll" is shown as the platform name.



==================================================

CHANGE #2

Booking Flow

==================================================



Current Flow:



Student

↓



Submit Doubt



↓



Book Session



This is confusing because Doubts already have a separate module/page.



Required Flow:



Student

↓



Book Session



↓



Select Subject



↓



View Available Calendar



↓



Select Available Date



↓



Select Available Time Slot



↓



Confirm Booking



The booking page should NOT force the student to submit a doubt first.



If the student wants to submit a doubt, they can use the separate "Submit Doubt" page.



Keep both modules independent.



Do NOT remove the Doubt module.



Only remove the dependency between Booking and Doubt.



==================================================

CHANGE #3

Calendar Based Booking

==================================================



Current UI:



Students manually select date/time which is confusing.



Replace it with a professional calendar-based booking interface.



Requirements:



\- Display a calendar view.

\- Week-by-week navigation.

\- Show only available dates.

\- Selecting a date should immediately show available time slots.

\- Booked slots should be disabled.

\- Past dates should be disabled.

\- Fully responsive.

\- Modern, clean UI.

\- Easy to understand for students.



The booking experience should be similar to modern appointment booking systems.



==================================================

FINAL VERIFICATION

==================================================



Verify:



✓ Welcome message displays "Drumroll Edu".



✓ Students can directly book sessions without first submitting a doubt.



✓ Submit Doubt remains a separate module.



✓ Booking page uses a weekly calendar view.



✓ Available slots display correctly.



✓ Booked slots are disabled.



✓ Past dates cannot be selected.



✓ Booking flow remains fully functional.



Do NOT redesign the entire website.



Only implement these requested changes while maintaining the existing project architecture and UI consistency.

