const appointmentDateElement = document.getElementById('app_appointment_date');
const timeSlotDiv = document.getElementById('app_timeSlotDiv');
const meetingTimeElement = document.getElementById('app_meeting_time');

if (appointmentDateElement) {
    let fullyBookedDates = [];
    let fullyBookedWeeks = [];
    let availableWeeks = [];
    let holidays = []; 
    const maxWeeksAvailable = 4;

    // Fetch fully booked dates and holidays
    Promise.all([
        fetch('/appointments/get-fully-booked-dates').then(response => response.json()),
        fetch('/appointments/get-holidays').then(response => response.json())
    ])
    .then(([fullyBookedData, holidayData]) => {
        const today = new Date();
        const todayDay = today.getDay();

        fullyBookedDates = fullyBookedData.fullyBookedDates
            .filter(dateString => {
                const date = new Date(dateString);
                return date >= today; // Only keep future fully booked dates
            })
            .map(dateString => {
                const date = new Date(dateString);
                return date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
            });

        
        holidays = holidayData.map(dateString => {
            const date = new Date(dateString); // Use local date without forcing UTC
            return date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
        });

        console.log("Fully booked dates:", fullyBookedDates);
        console.log("Holidays:", holidays);

        const weekBookings = {};

        fullyBookedDates.forEach(dateString => {
            const date = new Date(dateString);
            const weekNumber = getStandardWeekNumber(date);
            const dayOfWeek = date.getDay();

            if (dayOfWeek >= 3 && dayOfWeek <= 5) {
                if (!weekBookings[weekNumber]) {
                    weekBookings[weekNumber] = new Set();
                }
                weekBookings[weekNumber].add(dayOfWeek);
            }
        });

        const currentWeekNumber = getStandardWeekNumber(today);

        fullyBookedWeeks = Object.keys(weekBookings).filter(weekNumber => {
            const daysBooked = weekBookings[weekNumber];

            const wednesday = new Date(today);
            const thursday = new Date(today);
            const friday = new Date(today);

            wednesday.setDate(today.getDate() - today.getDay() + 3);
            thursday.setDate(today.getDate() - today.getDay() + 4);
            friday.setDate(today.getDate() - today.getDay() + 5);

            const wednesdayStr = wednesday.toISOString().split('T')[0];
            const thursdayStr = thursday.toISOString().split('T')[0];
            const fridayStr = friday.toISOString().split('T')[0];

            if (weekNumber == currentWeekNumber) {
                const isCurrentWeekFullyBooked = (
                    (daysBooked.has(3) || isPastEligibleDay(wednesday, today) || holidays.includes(wednesdayStr)) &&
                    (daysBooked.has(4) || isPastEligibleDay(thursday, today) || holidays.includes(thursdayStr)) &&
                    (daysBooked.has(5) || isPastEligibleDay(friday, today) || holidays.includes(fridayStr))
                );

                return isCurrentWeekFullyBooked;
            }

            return (
                (daysBooked.has(3) || holidays.includes(wednesdayStr)) &&
                (daysBooked.has(4) || holidays.includes(thursdayStr)) &&
                (daysBooked.has(5) || holidays.includes(fridayStr))
            );
        });

        console.log("Fully booked weeks:", fullyBookedWeeks);

        availableWeeks = calculateAvailableWeeks(today, todayDay, fullyBookedWeeks, maxWeeksAvailable);
        console.log("Available weeks:", availableWeeks);

        const endDate = getEndDateFromAvailableWeeks(today, availableWeeks);

        flatpickr(appointmentDateElement, {
            dateFormat: "Y-m-d",
            minDate: "today",  // Prevents booking for past dates including today
            maxDate: endDate, // Limit to the last day of available weeks
            enable: [
                function (date) {
                    const weekNumber = getStandardWeekNumber(date);
                    const year = date.getFullYear();
                    const month = ("0" + (date.getMonth() + 1)).slice(-2);
                    const day = ("0" + date.getDate()).slice(-2);
                    const dateString = `${year}-${month}-${day}`;
        
                    // Disable current and past dates
                    if (date < today) {
                        return false;
                    }
        
                    // Ensure the date falls within available weeks
                    const isWithinAllowedRange = date <= endDate && date >= today;
                    // Only enable valid days (Wednesday, Thursday, Friday)
                    const isValidDay = date.getDay() === 3 || date.getDay() === 4 || date.getDay() === 5;
                    const isFullyBooked = fullyBookedDates.includes(dateString);
                    const isHoliday = holidays.includes(dateString);
                    const isWeekEnabled = availableWeeks.includes(weekNumber);
        
                    // Ensure the date is in the future and matches available weeks/days
                    return isWithinAllowedRange && isValidDay && !isFullyBooked && !isHoliday && isWeekEnabled && date >= today;
                }
            ],
            onDayCreate: function (dObj, dStr, fp, dayElem) {
                const date = new Date(dayElem.dateObj);
                const today = new Date();
                today.setHours(0, 0, 0, 0); // Normalize today's date
        
                const year = date.getFullYear();
                const month = ("0" + (date.getMonth() + 1)).slice(-2);
                const day = ("0" + date.getDate()).slice(-2);
                const dateString = `${year}-${month}-${day}`;
        
                // If the date is in the past or today and not bookable anymore
                if (date <= today && isPastEligibleDay(date, today)) {
                    dayElem.classList.add('past-eligible-day');
                }
        
                // Remove 'fully-booked-date' class and add 'flatpickr-disabled' if the date is in the past
                if (date < today) {
                    dayElem.classList.remove('fully-booked-date');
                    dayElem.classList.add('flatpickr-disabled');
                    dayElem.disabled = true;
                }
        
                // Add 'fully-booked-date' class for fully booked future dates
                if (fullyBookedDates.includes(dateString)) {
                    dayElem.classList.add('fully-booked-date');
                }
        
                // Add 'holiday-date' class for holidays
                if (holidays.includes(dateString)) {
                    dayElem.classList.add('holiday-date');
                }
        
                // Add 'available-date' class for available dates
                const weekNumber = getStandardWeekNumber(date);
                const isValidDay = date.getDay() === 3 || date.getDay() === 4 || date.getDay() === 5;
                const isFullyBooked = fullyBookedDates.includes(dateString);
                const isHoliday = holidays.includes(dateString);
                const isWeekEnabled = availableWeeks.includes(weekNumber);
        
                if (isValidDay && !isFullyBooked && !isHoliday && isWeekEnabled && date > today) {
                    dayElem.classList.add('available-date');
                }
            },
            onChange: function (selectedDates, dateStr) {
                if (dateStr) {
                    fetchAvailableTimeSlots(dateStr);
                }
            }
        });
        
        
        
        
    })
    .catch(error => {
        console.error('Error fetching fully booked dates or holidays:', error);
    });
}

// Function to calculate the end date from available weeks
function getEndDateFromAvailableWeeks(today, availableWeeks) {
    if (availableWeeks.length === 0) return today; // No available weeks, so return today

    const lastAvailableWeek = availableWeeks[availableWeeks.length - 1];

    // Calculate the last day (Sunday) of the last available week
    const endOfLastAvailableWeek = new Date(today);
    endOfLastAvailableWeek.setDate(today.getDate() + ((lastAvailableWeek - getStandardWeekNumber(today)) * 7) + (6 - today.getDay()));

    return endOfLastAvailableWeek;
}

// Check if the day is a past eligible day
function isPastEligibleDay(date, today) {
    const currentWeekDay = today.getDay();
    const dateWeekDay = date.getDay();
    const isEligibleDay = [3, 4, 5].includes(dateWeekDay);
    const isTodayOrPast = date <= today;

    // Check if the date is in the same week as today
    let startOfWeek = new Date(today);
    startOfWeek.setDate(today.getDate() - currentWeekDay + (currentWeekDay === 0 ? -6 : 1));
    let endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6);

    return isEligibleDay && isTodayOrPast && date >= startOfWeek && date <= endOfWeek;
}


function fetchAvailableTimeSlots(date) {
    fetch(`/appointments/get-available-time-slots?date=${date}`)
        .then(response => response.json())
        .then(data => {
            meetingTimeElement.innerHTML = '<option value="">Select a time slot</option>';

            if (data.length > 0) {
                data.forEach(slot => {
                    let option = document.createElement('option');
                    option.value = slot;
                    option.textContent = slot;
                    meetingTimeElement.appendChild(option);
                });
                timeSlotDiv.style.display = 'block';
            } else {
                timeSlotDiv.style.display = 'none';
            }
        })
        .catch(error => console.error('Error fetching time slots:', error));
}

// Function to calculate the standard week number (Monday-Sunday)
function getStandardWeekNumber(date) {
    const tempDate = new Date(date);
    const startOfYear = new Date(tempDate.getFullYear(), 0, 1); // January 1st
    const diffInTime = tempDate - startOfYear;
    const diffInDays = Math.floor(diffInTime / (1000 * 60 * 60 * 24)) + 1;
    const dayOfWeek = startOfYear.getDay();
    const startOffset = (dayOfWeek === 0) ? 1 : 0; // If it's Sunday, adjust to Monday
    const weekNumber = Math.ceil((diffInDays + dayOfWeek - startOffset) / 7);
    return weekNumber;
}

// Function to calculate available weeks (excluding past and fully booked weeks)
function calculateAvailableWeeks(today, todayDay, fullyBookedWeeks, maxWeeksAvailable) {
    let enabledWeeks = [];
    let weekIndex = 0;

    // If today is Friday or later, skip the current week (week of todayDay > 4)
    if (todayDay >= 5) {
        weekIndex = 1; // Start from next week
    }

    const todayWeekNumber = getStandardWeekNumber(today);

    // Continue adding weeks until we have maxWeeksAvailable
    while (enabledWeeks.length < maxWeeksAvailable) {
        const weekNumber = todayWeekNumber + weekIndex;

        // Calculate the start and end of the week (Monday to Sunday)
        const startOfWeek = new Date(today);
        startOfWeek.setDate(today.getDate() + (weekIndex * 7) - today.getDay()); // Get the start date of the week (Monday)

        const endOfWeek = new Date(startOfWeek);
        endOfWeek.setDate(startOfWeek.getDate() + 6); // End of week (Sunday)

        // Exclude fully booked weeks and past weeks
        if (!fullyBookedWeeks.includes(String(weekNumber)) && endOfWeek >= today) {
            enabledWeeks.push(weekNumber);
        }

        weekIndex++;
    }
    return enabledWeeks;
}


// OTP Input Management Logic
// Minified by Diwakar Sinha at 01-01-2025
const setupForm = (e, t) => {
    const n = document.getElementById(e),
        a = [...n.querySelectorAll("input[type=text]")],
        l = n.querySelector(t),
        s = (e) => {
            const t = a.indexOf(e.target);
            /^[0-9]{1}$/.test(e.key) || "Backspace" === e.key || "Delete" === e.key || "Tab" === e.key || e.metaKey || e.preventDefault(),
                ("Delete" === e.key || "Backspace" === e.key) && t >= 0 && ("" === a[t].value ? t > 0 && (a[t - 1].focus(), (a[t - 1].value = "")) : (a[t].value = ""), e.preventDefault());
        },
        c = (e) => {
            const { target: t } = e,
                n = a.indexOf(t);
            t.value && n < a.length - 1 ? a[n + 1].focus() : n === a.length - 1 && l.focus();
        },
        r = (e) => {
            e.target.select();
        },
        u = (e) => {
            e.preventDefault();
            const t = e.clipboardData.getData("text");
            if (!/^[0-9]{1,}$/.test(t)) return;
            const n = t.split("").slice(0, a.length);
            a.forEach((e, t) => (e.value = n[t] || "")), n.length === a.length && l.focus();
        };
    a.forEach((e) => {
        e.addEventListener("input", c), e.addEventListener("keydown", s), e.addEventListener("focus", r), e.addEventListener("paste", u);
    });
};

// Initialize setupForm for OTP forms
// Minified by Diwakar Sinha at 01-01-2025
setupForm("app-otp-form", "#appVerifyMobileOtpBtn"), setupForm("app-otp-form-email", "#appVerifyEmailOtpBtn");
var app_meetingPurpose = document.getElementById("app_meetingPurpose");
app_meetingPurpose.addEventListener("change", function () {
    document.getElementById("app_meetingDescriptionDiv").style.display = "" !== this.value ? "block" : "none";
});

// Appointment Form Validation
// Minified by Diwakar Sinha at 01-01-2025
$(document).ready(function () {
    function e() {
        $("#app_Yes").is(":checked") ? ($("#app_ifyes").show(), $("#app_ifYesNotChecked").hide()) : ($("#app_ifyes").hide(), $("#app_ifYesNotChecked").show());
    }
    function r() {
        $("#app_isStakeholder").is(":checked") ? $("#app_ifStakeholder").show() : $("#app_ifStakeholder").hide();
    }
    $("#app_AppointmentSubmitButton").click(function (e) {
        e.preventDefault();
        var r = document.getElementById("appointment_form");
        (function () {
            let e = null,
                r = !0;
            [
                { id: "#app_fullname", errorId: "#app_fullnameError" },
                { id: "#app_mobile", errorId: "#app_mobileError" },
                { id: "#app_email", errorId: "#app_emailError" },
                { id: "#app_pan_number", errorId: "#app_panNumberError" },
            ].forEach(function (i) {
                const o = $(i.id),
                    t = $(i.errorId);
                o.on("input", function () {
                    "" !== o.val().trim() && (o.removeClass("required"), t.hide());
                }),
                    "" === o.val().trim() ? (o.addClass("required"), t.text("This field is required").show(), (r = !1), null === e && (e = o)) : (o.removeClass("required"), t.hide());
            });
            const i = $("#app_mobile"),
                o = $("#app_mobileError"),
                t = i.attr("data-id");
            function a() {
                const a = i.val().trim();
                "" === a
                    ? (o.text("Mobile Number is required"), o.show(), (r = !1), null === e && (e = i))
                    : 10 !== a.length
                    ? (o.text("Mobile Number must be exactly 10 digits"), o.show(), (r = !1), null === e && (e = i))
                    : "0" === t
                    ? (o.text("Please verify your mobile number"), o.show(), (r = !1), null === e && (e = i))
                    : o.hide();
            }
            a(),
                i.on("input", function () {
                    "" !== i.val().trim() && (e = null), a();
                });
            const l = $("#app_email"),
                n = $("#app_emailError"),
                p = l.val().trim(),
                d = l.attr("data-id");
            "" === p ? (n.text("Email is required"), n.show(), (r = !1), null === e && (e = l)) : "0" === d ? (n.text("Please verify your email"), n.show(), (r = !1), null === e && (e = l)) : n.hide();
            const s = $("#app_pan_number"),
                u = $("#app_panNumberError"),
                m = s.val().trim();
            "" === m
                ? (s.addClass("required"), u.text("This field is required").show(), (r = !1), null === e && (e = s))
                : 10 !== m.length
                ? (s.addClass("required"), u.text("PAN Number must be exactly 10 characters").show(), (r = !1), null === e && (e = s))
                : (s.removeClass("required"), u.hide());
            if ($("#app_Yes").is(":checked")) {
                [
                    { id: "#app_localityFill", errorId: "#app_localityFillError" },
                    { id: "#app_blocknoFill", errorId: "#app_blocknoFillError" },
                    { id: "#app_plotnoFill", errorId: "#app_plotnoFillError" },
                ].forEach(function (i) {
                    const o = $(i.id),
                        t = $(i.errorId);
                    o.on("input", function () {
                        "" !== o.val().trim() && (o.removeClass("required"), t.hide());
                    }),
                        "" === o.val().trim() ? (o.addClass("required"), t.text("This field is required").show(), (r = !1), null === e && (e = o)) : (o.removeClass("required"), t.hide());
                });
            } else {
                [
                    { id: "#app_locality", errorId: "#app_localityError" },
                    { id: "#app_block", errorId: "#app_blockError" },
                    { id: "#app_plot", errorId: "#app_plotError" },
                ].forEach(function (i) {
                    const o = $(i.id),
                        t = $(i.errorId);
                    o.on("input", function () {
                        "" !== o.val().trim() && (o.removeClass("required"), t.hide());
                    }),
                        "" === o.val().trim() ? (o.addClass("required"), t.text("This field is required").show(), (r = !1), null === e && (e = o)) : (o.removeClass("required"), t.hide());
                });
            }
            if ($("#app_isStakeholder").is(":checked")) {
                const w = $("#app_stakeholderProof"),
                    I = $("#app_stakeholderProofError");
                function c() {
                    const i = w[0].files;
                    let o = !1,
                        t = !0;
                    if (i.length > 0) {
                        o = !0;
                        for (let e = 0; e < i.length; e++) {
                            if (!i[e].name.endsWith(".pdf")) {
                                t = !1;
                                break;
                            }
                        }
                    }
                    o
                        ? t
                            ? (w.removeClass("required"), I.hide())
                            : (w.addClass("required"), I.text("Only PDF file is allowed").show(), (r = !1), null === e && (e = w))
                        : (w.addClass("required"), I.text("File is required").show(), (r = !1), null === e && (e = w));
                }
                c(),
                    w.on("change", function () {
                        (e = null), c();
                    });
            }
            [
                { id: "#app_natureOfVisit", errorId: "#app_natureOfVisitError" },
                { id: "#app_meetingPurpose", errorId: "#app_meetingPurposeError" },
                { id: "#app_appointment_date", errorId: "#app_appointmentDateError" },
            ].forEach(function (i) {
                const o = $(i.id),
                    t = $(i.errorId);
                o.on("input", function () {
                    "" !== o.val().trim() && (o.removeClass("required"), t.hide());
                }),
                    "" === o.val().trim() ? (o.addClass("required"), t.text("This field is required").show(), (r = !1), null === e && (e = o)) : (o.removeClass("required"), t.hide());
            });
            const h = $("#app_meetingPurpose"),
                _ = $("#app_meetingDescription"),
                f = $("#app_meetingDescriptionError");
            function v() {
                const i = h.val().trim(),
                    o = _.val().trim();
                "" !== i && "" === o ? (f.text("Meeting Description is required"), f.show(), _.addClass("required"), (r = !1), null === e && (e = _)) : (f.hide(), _.removeClass("required"));
            }
            v(),
                h.on("input", v),
                _.on("input", function () {
                    "" !== _.val().trim() && (e = null), v();
                });
            const q = $("#app_appointment_date"),
                E = $("#app_meeting_time"),
                b = q.val().trim(),
                C = E.val().trim(),
                k = $("#app_meetingTimeError");
            "" !== b && "" === C ? (k.text("Time Slot is required"), k.show(), E.addClass("required"), (r = !1), null === e && (e = E)) : (k.hide(), E.removeClass("required"));
            null !== e && e.focus();
            return r;
        })() && r.submit();
    }),
        e(),
        $("#app_Yes").change(function () {
            e();
        }),
        r(),
        $("#app_isStakeholder").change(function () {
            r();
        });
});