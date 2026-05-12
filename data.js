const portalData = {
    syllabus: {
        1: { name: "1st Semester", subjects: [
            { title: "C-Programming", path: "syllabus-pdf/BIT102 - C Programming - Microsyllabus - bitportal.pdf", level: "Hard", tag: "Coding" },
            { title: "Digital Logic", path: "syllabus-pdf/BIT103 - Digital Logic - Microsyllabus - bitportal.pdf", level: "Medium", tag: "Hardware" },
            { title: "Basic Mathematics", path: "syllabus-pdf/MTH104 - Basic Mathematics - Mircosyllabus - bitportal.pdf", level: "Hard", tag: "Math" },
            { title: "Introduction to Information & Technology", path: "syllabus-pdf/BIT101 - Introduction to Information Technology - Microsyllabus - bitportal.pdf", level: "Easy", tag: "Theory" },
            { title: "Sociology", path: "syllabus-pdf/SCO105 - Sociology - Microsyllabus - bitportal.pdf", level: "Easy", tag: "Theory" },
        ]},
        2: { name: "2nd Semester", subjects: [
            { title: "OOPS", path: "syllabus-pdf/BIT102 - C Programming - Microsyllabus - bitportal.pdf", level: "Hard", tag: "Coding" },
            { title: "Microprocessor", path: "syllabus-pdf/Microprocessor-and-Computer-Architecture-BIT151.pdf", level: "Hard", tag: "Hardware" },
            { title: "Discrete Structure", path: "syllabus-pdf/Discrete-Structure-BIT152 bitportal.pdf", level: "Medium", tag: "Math" },
            { title: "Economics", path: "syllabus-pdf/Economics-ECO155 bitportal.pdf", level: "Easy", tag: "Theory" }
        ]},
        3: { name: "3rd Semester", subjects: [
            { title: "Data Structures (DSA)", path: "syllabus-pdf/Data-Structures-and-Algorithms-BIT201 (1).pdf", level: "Extreme", tag: "Coding" },
            { title: "Database (DBMS)", path: "syllabus-pdf/Database-Management-System-BIT202 bitportal.pdf", level: "Medium", tag: "Data" },
            { title: "Numerical Methods", path: "syllabus-pdf/Numerical-Methods-BIT203 bitportal.pdf", level: "Medium", tag: "Math" },
            { title: "Operating Systems", path: "syllabus-pdf/Operating-Systems-BIT204 bitportal.pdf", level: "Hard", tag: "System" }
        ]},
        4: { name: "4th Semester", subjects: [
            { title: "Web Technology", path: "syllabus-pdf/BIT251 Web Technology I.pdf", level: "Medium", tag: "Web" },
            { title: "Artificial Intelligence", path: "syllabus-pdf/BIT252 Artificial Intelligence.pdf", level: "Easy", tag: "Process" },
            { title: "System Analysis and Design", path: "syllabus-pdf/BIT253 Systems Analysis and Design.pdf", level: "Hard", tag: "Network" },
            { title: "Network and Data Communications", path: "syllabus-pdf/BIT254 Network and Data Communications.pdf", level: "Hard", tag: "Coding" },
            { title: "Operations Research", path: "syllabus-pdf/ORS255 Operations Research.pdf", level: "Medium", tag: "Math" }
        ]},
        5: { name: "5th Semester", subjects: [
            { title: "Web Technology II", path: "syllabus-pdf/Web Technology II BIT301.pdf", level: "Extreme", tag: "Coding" },
            { title: "Software Engineering", path: "syllabus-pdf/Software Engineering BIT302.pdf", level: "Hard", tag: "Visual" },
            { title: "Information Security", path: "syllabus-pdf/Information Security BIT303.pdf", level: "Easy", tag: "Management" },
            { title: "Computer Graphics", path: "syllabus-pdf/Computer Graphics BIT304.pdf", level: "Hard", tag: "Theory" },
            { title: "Technical Writing", path: "syllabus-pdf/Technical Writing ENG305.pdf", level: "Easy", tag: "Theory" },
        ]},
        6: { name: "6th Semester", subjects: [
            { title: "Net-Centric Computing", path: "syllabus-pdf/NET Centric Computing BIT351.pdf", level: "Extreme", tag: "Coding" },
            { title: "Database Administration", path: "syllabus-pdf/Database Administration BIT352.pdf", level: "Hard", tag: "Database" },
            { title: "Management Information System", path: "syllabus-pdf/Management Information System BIT353.pdf", level: "Medium", tag: "Theory" },
            { title: "Research Methodology", path: "syllabus-pdf/Research Methodology RSM354.pdf", level: "Medium", tag: "Theory" },
            { title: "Geographical Information System", path: "syllabus-pdf/Geographical Information System BIT355.pdf", level: "Easy", tag: "Theory" },
            { title: "Psychology", path: "syllabus-pdf/Psychology PSY359.pdf", level: "Easy", tag: "Theory" },
            { title: "Wireless Networking", path: "syllabus-pdf/Wireless Networking BIT357.pdf", level: "Medium", tag: "Theory" },
            { title: "Society And Ethics In IT", path: "syllabus-pdf/Society and Ethics in IT BIT358.pdf", level: "Medium", tag: "Theory" }
        ]},
        7: { name: "7th Semester", subjects: [
            { title: "Advanced Java Programming", path: "syllabus-pdf/Advanced-Java-Programming-BIT401.pdf", level: "Hard", tag: "Development" },
            { title: "Software Project Management", path: "syllabus-pdf/BIT 402 - Software Project Management.pdf", level: "Easy", tag: "Management" },
            { title: "E-Commerce", path: "syllabus-pdf/BIT 403 - E-Commerce.pdf", level: "Medium", tag: "Web Tech" },
            { title: "Project Work", path: "syllabus-pdf/BIT401.pdf", level: "Extreme", tag: "Practical" },
            { title: "DSS And Expert System", path: "syllabus-pdf/BIT 405 - DSS And Expert System.pdf", level: "Hard", tag: "AI/Data" },
            { title: "Mobile Application Development", path: "syllabus-pdf/BIT 406 - Mobile Application Development.pdf", level: "Hard", tag: "Mobile" },
            { title: "Simulation And Modeling", path: "syllabus-pdf/BIT 407 - Simulation And Modeling.pdf", level: "Medium", tag: "Mathematics" },
            { title: "Cloud Computing", path: "syllabus-pdf/BIT 408 - Cloud Computing.pdf", level: "Medium", tag: "Infrastructure" },
            { title: "Marketing", path: "syllabus-pdf/MGT 409 - Marketing (1).pdf", level: "Easy", tag: "Business" }
        ]},
        8: { name: "8th Semester", subjects: [
            { title: "Network System Administration", path: "syllabus-pdf/BIT 451 - Network System Administration.pdf", level: "Hard", tag: "Infrastructure" },
            { title: "E-Governance", path: "syllabus-pdf/BIT 452 - E-Governance.pdf", level: "Easy", tag: "Digital Policy" },
            { title: "Internship", path: "", level: "Extreme", tag: "Professional" },
            { title: "Data Warehousing And Data Mining", path: "syllabus-pdf/BIT 454 - Data Warehousing And Data Mining.pdf", level: "Hard", tag: "Analytics" },
            { title: "Knowledge Management", path: "syllabus-pdf/BIT 455 - Knowledge Management.pdf", level: "Medium", tag: "Information" },
            { title: "Image Processing", path: "syllabus-pdf/BIT 456 - Image Processing.pdf", level: "Hard", tag: "AI/Vision" },
            { title: "Network Security", path: "syllabus-pdf/BIT 457 - Network Security.pdf", level: "Hard", tag: "Security" },
            { title: "Introduction To Telecommunications", path: "syllabus-pdf/BIT 458 - Introduction To Telecommunications.pdf", level: "Medium", tag: "Communication" }
        ]}
    }
};

function loadSyllabus(sem) {
    const title = document.getElementById('sem-title');
    const list = document.getElementById('subject-list');
    list.innerHTML = "";
    const semData = portalData.syllabus[sem];

    if (semData) {
        title.innerText = semData.name;
        semData.subjects.forEach(subject => {
            const item = document.createElement('div');
            item.className = 'subject-item';

            const levelColor = subject.level === "Extreme" ? "#ef4444"
                             : subject.level === "Hard"    ? "#f59e0b"
                             : subject.level === "Medium"  ? "#38bdf8"
                             : "#10b981";

            item.innerHTML = `
                <div style="text-align:left;">
                    <span style="display:block; font-weight:bold; font-size:1rem;">${subject.title}</span>
                    <small style="color:${levelColor}">● ${subject.level}</small>
                    <small style="background:#334155; padding:2px 8px; border-radius:4px; margin-left:8px;">${subject.tag}</small>
                </div>
                <div style="display:flex; gap:6px; align-items:center;">
                    ${subject.path ? `<a href="${subject.path}" target="_blank" class="view-btn">View PDF</a>
                    <a href="${subject.path}" download class="dl-btn">↓</a>` : `<span style="font-size:.75rem; color:#64748b;">No PDF</span>`}
                </div>
            `;
            list.appendChild(item);
        });
    } else {
        title.innerText = "Error";
        list.innerHTML = "Semester data not found.";
    }
}

function searchSubject() {
    let input = document.getElementById('searchBar').value.toLowerCase();
    let items = document.getElementsByClassName('subject-item');
    Array.from(items).forEach(item => {
        let text = item.innerText.toLowerCase();
        item.style.display = text.includes(input) ? "flex" : "none";
    });
}

window.onload = () => loadSyllabus(1);