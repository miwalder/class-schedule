# HumHub Class Schedule 🏫

*A comprehensive timetable and lesson planning module for HumHub. Features include global school year management, space-level class schedules, granular visibility permissions for teacher teams, and a safe presentation mode.*

The **Class Schedule** module transforms HumHub into a full-fledged digital planning platform for schools and educational institutions. It allows teachers to manage timetables directly within classroom spaces, prepare lessons collaboratively as a team, and sync appointments seamlessly with the HumHub calendar.

---

## ✨ Key Features

* **Global Structural Data:** Centralized management of lesson periods (grids), school years, and holiday blocks by the network administrator.
* **Space-Specific Schedules:** Every class (or space) receives its own dedicated timetable.
* **Collaborative Lesson Planning:** Teachers can store daily plans, notes, and homework for every individual lesson.
* **Smart Calendar Integration:** The module integrates natively with the HumHub `calendar` module. It automatically calculates the current school week (SW) and calendar week (CW), and enters holidays as all-day events.
* **Granular Visibility:** Space settings allow you to define exactly who can see the lesson planning (e.g., only the "Teachers" group). Students still see holidays and school weeks in the calendar, but internal teacher notes remain hidden.
* **📽️ Beamer Mode (Presentation Mode):** An innovative, server-side presentation mode. With one click on the floating button in the calendar, all lesson subjects are immediately hidden. This allows the calendar to be safely projected onto a wall without leaking sensitive preparation data.

---

## ⚙️ Installation

1.  Download the module or clone the repository.
2.  Move the `class-schedule` folder into the modules directory of your HumHub installation:  
    `protected/modules/class-schedule`
3.  Log in to HumHub as an administrator.
4.  Navigate to **Administration -> Modules**.
5.  Click **Install** for the "Class Schedule" module and then **Activate**.

---

## 🚀 Quick Start

### 1. Global Setup (Admin)
Before the module can be used in spaces, the global framework must be established:
* Go to **Administration -> Class Schedule**.
* Create at least one **School Year** (start and end date).
* Define the **Lesson Periods** (e.g., 1st Period: 07:30 - 08:15).
* (Optional) Enter global **Holidays**.

### 2. Space Setup (Teacher / Space Admin)
* Go to the desired space (e.g., "Class 8b").
* Activate the module under **Space Settings -> Modules**.
* Click the **Gear Icon** (Configure) in the top right of the Class Schedule view. Select the user group authorized to see the lessons in the calendar.

### 3. Filling the Schedule
* Select the current school year from the dropdown menu.
* Click on the empty fields to **Add Subject** and define the subject name and color.

---

## 💡 Beamer Mode
When you open the space calendar, a floating button appears in the bottom left (**Beamer: OFF**). 
Clicking it activates the mode for the current session. The module then blocks the server-side transmission of all lesson entries to the calendar. You can now share the calendar via a projector with the class without any concerns. Another click deactivates the mode.

---

## 🛠️ System Requirements
* HumHub Version 1.15 or higher
* Activated official HumHub `calendar` module
* PHP 8.0 or higher

---

## 📝 License
This module is open-source and licensed under the [General Public License].
