@extends('layouts.app')

@section('content')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ... (your existing CSS) ... */
        body {
            background-color: #f3f4f6;
            /* Light gray background */
            font-family: 'Inter', sans-serif;
            color: #374151;
            /* Dark gray text */
        }

        .modern-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 2.5rem;
            max-height: calc(100vh - 4rem);
            overflow-y: auto;
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .modern-card::-webkit-scrollbar {
            display: none;
        }

        .modern-header {
            color: #1f2937;
            font-weight: 700;
            font-size: 2.25rem;
        }

        .modern-input,
        .modern-select {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            color: #374151;
            transition: all 0.2s ease;
            border-radius: 8px;
        }

        .modern-input:focus,
        .modern-select:focus {
            outline: none;
            border-color: #3b82f6;
            /* Blue focus ring */
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .modern-btn {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s ease;
            padding: 12px 24px;
            background-color: #2563eb;
            color: #ffffff;
            border: none;
        }

        .modern-btn:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .modern-alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #34d399;
            border-radius: 8px;
            padding: 1rem;
        }

        .modern-alert-info {
            background-color: #eef2ff;
            color: #4338ca;
            border: 1px solid #818cf8;
            border-radius: 8px;
            padding: 1rem;
        }

        .modern-alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 1rem;
        }

        .barcode-scanner-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(4px);
        }

        .barcode-scanner-content {
            position: relative;
            background: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            width: 90%;
            max-width: 550px;
        }

        .barcode-scanner-close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 2rem;
            color: #9ca3af;
            cursor: pointer;
            transition: color 0.2s;
        }

        .barcode-scanner-close:hover {
            color: #1f2937;
        }

        #scanner-container {
            width: 100%;
            height: 350px;
            border-radius: 8px;
            overflow: hidden;
        }

        /* Custom message box for scanner errors */
        #scanner-message {
            color: #1f2937;
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            padding: 0.75rem;
            border-radius: 8px;
            text-align: center;
            margin-top: 1rem;
            display: none;
        }
    </style>
    <div class="container mt-8 max-w-4xl mx-auto modern-card">
        <h2 class="text-center mb-8 modern-header">
            Borrowing Details
        </h2>

        @if ($errors->any())
            <div class="p-4 mb-4 modern-alert-error">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="p-4 mb-4 modern-alert-success">{{ session('success') }}</div>
        @endif

        <div id="form-message" class="modern-alert-error mb-4 hidden"></div>

        <form id="borrowing-form" action="{{ route('admin.borrowings.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Member Selection --}}
            <div class="p-6 border-b border-gray-200">
                <h4 class="text-xl font-semibold mb-4 text-gray-800">🧑‍🎓 Member Details</h4>
                <div class="space-y-4">
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Select Year</label>
                        <select id="year" class="w-full p-3 modern-select" required>
                            <option value="">-- Select Year --</option>
                            @foreach ($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="major" class="block text-sm font-medium text-gray-700 mb-1">Select Major</label>
                        <select id="major" class="w-full p-3 modern-select" required disabled>
                            <option value="">-- Select Major --</option>
                        </select>
                    </div>

                    <div>
                        <label for="roll_no" class="block text-sm font-medium text-gray-700 mb-1">Select Roll No or Scan QR
                            Code</label>
                        <div class="flex items-center space-x-2">
                            <select id="roll_no" class="w-full p-3 modern-select" required disabled>
                                <option value="">-- Select Roll No --</option>
                            </select>
                            <button type="button" id="scan-member-qr-btn" class="p-3 modern-btn">Scan QR 🤳</button>
                        </div>
                    </div>
                    <div>
                        <label for="member_name" class="block text-sm font-medium text-gray-700 mb-1">Member Name</label>
                        <input type="text" id="member_name" class="w-full p-3 modern-input" readonly>
                    </div>
                </div>
                <input type="hidden" name="member_id" id="member_id">
            </div>

            {{-- Book Selection --}}
            <div class="p-6 border-b border-gray-200">
                <h4 class="text-xl font-semibold mb-4 text-gray-800">📚 Book Details</h4>
                <div class="space-y-4">
                    <div>
                        <label for="book_isbn" class="block text-sm font-medium text-gray-700 mb-1">Enter Book ISBN or Scan
                            Barcode</label>
                        <div class="flex items-center space-x-2">
                            <input type="text" id="book_isbn" name="book_isbn" class="w-full p-3 rounded-md modern-input"
                                placeholder="Enter ISBN" />
                            <button type="button" id="scan-barcode-btn" class="p-3 modern-btn">Scan Barcode 🔍</button>
                        </div>
                    </div>

                    <div>
                        <label for="book_title" class="block text-sm font-medium text-gray-700 mb-1">Book Title</label>
                        <input type="text" id="book_title" class="w-full p-3 modern-input" readonly>
                    </div>
                </div>
                <input type="hidden" name="book_id" id="book_id">
            </div>

            {{-- Due Date --}}
            <div class="p-6">
                <h4 class="text-xl font-semibold mb-4 text-gray-800">⏳ Due Date</h4>
                <div class="space-y-4">
                    <div>
                        <label for="due_date_message" class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                        <div id="due_date_message" class="p-3 modern-input text-sm">
                            The due date will be calculated automatically by the system.
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full p-3 modern-btn mt-6">Submit Borrowing ✅</button>
        </form>
    </div>


    <div id="barcode-scanner-modal" class="barcode-scanner-modal">
        <div class="barcode-scanner-content">
            <span class="barcode-scanner-close">&times;</span>
            <div id="scanner-container"></div>
            {{-- Custom message box for scanner errors --}}
            <div id="scanner-message"></div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>

    <script>
        // ====== Member Data & Elements ======
        const members = @json($members);
        const yearSelect = document.getElementById('year');
        const majorSelect = document.getElementById('major');
        const rollNoSelect = document.getElementById('roll_no');
        const memberNameInput = document.getElementById('member_name');
        const memberIdInput = document.getElementById('member_id');
        const scanMemberQrBtn = document.getElementById('scan-member-qr-btn');

        // ====== Book Data & Elements ======
        const books = @json($books);
        const bookIsbnInput = document.getElementById('book_isbn');
        const bookTitleInput = document.getElementById('book_title');
        const bookIdInput = document.getElementById('book_id');
        const scanBarcodeBtn = document.getElementById('scan-barcode-btn');

        // ====== Form & Message Elements ======
        const form = document.getElementById('borrowing-form');
        const formMessage = document.getElementById('form-message');

        // ====== Scanner Logic Elements ======
        const scannerModal = document.getElementById('barcode-scanner-modal');
        const closeScannerBtn = document.querySelector('.barcode-scanner-close');
        const scannerMessage = document.getElementById('scanner-message');
        let scannerMode = ''; // 'member' or 'book'
        let qrCodeScanner = null; // Variable to hold the Html5QrcodeScanner instance

        // Helper function to show a custom message
        function showFormMessage(message, isError = true) {
            formMessage.textContent = message;
            formMessage.style.display = 'block';
            formMessage.className = isError ? 'modern-alert-error mb-4' : 'modern-alert-success mb-4';
        }

        // Helper function to populate the major dropdown based on year
        function populateMajors(selectedYear) {
            majorSelect.innerHTML = '<option value="">-- Select Major --</option>';
            majorSelect.disabled = !selectedYear;
            if (!selectedYear) return;

            let majorsForYear = [];
            if (selectedYear == '1') {
                majorsForYear = ['CST'];
            } else if (['2', '3', '4', '5'].includes(selectedYear)) {
                majorsForYear = ['CS', 'CT'];
            }

            majorsForYear.forEach(major => {
                const option = document.createElement('option');
                option.value = major;
                option.textContent = major;
                majorSelect.appendChild(option);
            });
        }

        // Helper function to populate the roll number dropdown based on year and major
        function populateRollNumbers(selectedYear, selectedMajor) {
            rollNoSelect.innerHTML = '<option value="">-- Select Roll No --</option>';
            rollNoSelect.disabled = true;
            if (!selectedYear || !selectedMajor) return;

            const filteredMembers = members.filter(m => m.year == selectedYear && m.major === selectedMajor);
            filteredMembers.forEach(member => {
                const option = document.createElement('option');
                // The value is the member_id, but the text is the roll_no for display
                option.value = member.member_id;
                option.textContent = member.roll_no;
                rollNoSelect.appendChild(option);
            });
            rollNoSelect.disabled = filteredMembers.length === 0;
        }

        // Centralized function to find and populate all member details by member_id
        function findAndPopulateMemberById(memberId) {
            if (!memberId) {
                memberNameInput.value = '';
                memberIdInput.value = '';
                return;
            }

            // Find the member where the 'member_id' matches the selected value
            const member = members.find(m => m.member_id.toString() === memberId.toString());

            if (member) {
                // Populate the hidden member_id and visible name
                memberIdInput.value = member.member_id;
                memberNameInput.value = member.name;
            } else {
                // Clear fields if no member is found
                memberNameInput.value = '';
                memberIdInput.value = '';
                showFormMessage('Member not found with this ID.', true);
            }
        }

        // Centralized function to find and populate all member details by roll_no (for scanner)
        function findAndPopulateMemberByRollNo(rollNo) {
            if (!rollNo) {
                // Clear fields
                yearSelect.value = '';
                majorSelect.innerHTML = '<option value="">-- Select Major --</option>';
                majorSelect.disabled = true;
                rollNoSelect.innerHTML = '<option value="">-- Select Roll No --</option>';
                rollNoSelect.disabled = true;
                memberNameInput.value = '';
                memberIdInput.value = '';
                return;
            }

            // Find the member where the 'roll_no' matches the scanned value
            const member = members.find(m => m.roll_no === rollNo);

            if (member) {
                // Populate all dropdowns and fields based on the found member
                yearSelect.value = member.year;
                populateMajors(member.year);
                majorSelect.value = member.major;
                populateRollNumbers(member.year, member.major);
                rollNoSelect.value = member.member_id;
                memberNameInput.value = member.name;
                memberIdInput.value = member.member_id;
                showScannerMessage(`Member '${member.name}' has been selected.`, false);
            } else {
                // Clear fields and show error if no member is found
                yearSelect.value = '';
                majorSelect.innerHTML = '<option value="">-- Select Major --</option>';
                majorSelect.disabled = true;
                rollNoSelect.innerHTML = '<option value="">-- Select Roll No --</option>';
                rollNoSelect.disabled = true;
                memberNameInput.value = '';
                memberIdInput.value = '';
                showScannerMessage('Member not found with this roll number.', true);
            }
        }

        // Function to initialize the QR code scanner
        function initQrScanner() {
            qrCodeScanner = new Html5QrcodeScanner(
                "scanner-container", {
                    fps: 10,
                    qrbox: 250
                }, false);

            qrCodeScanner.render(
                (decodedText) => {
                    console.log("QR Code detected and processed: " + decodedText);
                    findAndPopulateMemberByRollNo(decodedText);
                    stopScanner();
                    scannerModal.style.display = 'none';
                },
                (errorMessage) => {
                    // Ignore the continuous stream of errors to avoid console spam
                }
            );
            showScannerMessage('Scanning for QR code...');
        }


        // Centralized function to find and populate book details
        function findAndPopulateBook(isbn) {
            bookIsbnInput.value = isbn;
            const book = books.find(b => b.isbn.toString() === isbn);

            if (book) {
                bookTitleInput.value = book.title;
                bookIdInput.value = book.id;
                bookIsbnInput.readOnly = true;
                showScannerMessage(`Book '${book.title}' has been selected.`, false);
            } else {
                bookTitleInput.value = 'No book found with this ISBN';
                bookIdInput.value = '';
                bookIsbnInput.readOnly = false;
                showScannerMessage('No book found with this ISBN.', true);
            }
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const memberIdFromUrl = urlParams.get('member_id');
            const bookIdFromUrl = urlParams.get('book_id');

            // Handle book auto-population
            if (bookIdFromUrl) {
                const book = books.find(b => b.id.toString() === bookIdFromUrl);
                if (book) {
                    findAndPopulateBook(book.isbn);
                }
            }

            // Handle member auto-population
            if (memberIdFromUrl) {
                findAndPopulateMemberById(memberIdFromUrl);
            }
        });

        yearSelect.addEventListener('change', () => {
            const selectedYear = yearSelect.value;
            populateMajors(selectedYear);
            majorSelect.value = '';
            rollNoSelect.innerHTML = '<option value="">-- Select Roll No --</option>';
            rollNoSelect.disabled = true;
            memberNameInput.value = '';
            memberIdInput.value = '';
        });

        majorSelect.addEventListener('change', () => {
            const selectedYear = yearSelect.value;
            const selectedMajor = majorSelect.value;
            populateRollNumbers(selectedYear, selectedMajor);
            rollNoSelect.value = '';
            memberNameInput.value = '';
            memberIdInput.value = '';
        });

        rollNoSelect.addEventListener('change', () => {
            // This listener now correctly passes the member_id from the dropdown value
            const selectedMemberId = rollNoSelect.value;
            findAndPopulateMemberById(selectedMemberId);
        });


        bookIsbnInput.addEventListener('input', (event) => {
            const enteredIsbn = event.target.value.trim();
            if (!enteredIsbn) {
                bookTitleInput.value = '';
                bookIdInput.value = '';
                return;
            }
            findAndPopulateBook(enteredIsbn);
        });

        // Form submission listener for client-side validation
        form.addEventListener('submit', (event) => {
            if (!memberIdInput.value) {
                event.preventDefault();
                showFormMessage('Please select a member before submitting.');
                return;
            }
            if (!bookIdInput.value) {
                event.preventDefault();
                showFormMessage('Please select a book by entering or scanning an ISBN before submitting.');
            }
        });

        // Scanner Event Listeners
        scanMemberQrBtn.addEventListener('click', () => {
            scannerMode = 'member';
            scannerModal.style.display = 'flex';
            initQrScanner();
        });

        scanBarcodeBtn.addEventListener('click', () => {
            scannerMode = 'book';
            scannerModal.style.display = 'flex';
            initBarcodeScanner(["ean_reader", "code_128_reader", "code_39_reader"]);
        });

        closeScannerBtn.addEventListener('click', () => {
            stopScanner();
            scannerModal.style.display = 'none';
            scannerMessage.style.display = 'none';
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === "Escape" && scannerModal.style.display === 'flex') {
                stopScanner();
                scannerModal.style.display = 'none';
                scannerMessage.style.display = 'none';
            }
        });

        function showScannerMessage(message, isError = false) {
            scannerMessage.textContent = message;
            scannerMessage.style.display = 'block';
            scannerMessage.style.backgroundColor = isError ? '#fee2e2' : '#dbeafe';
            scannerMessage.style.borderColor = isError ? '#fca5a5' : '#93c5fd';
            scannerMessage.style.color = isError ? '#991b1b' : '#1e40af';
        }

        // Function to initialize the barcode scanner (using QuaggaJS)
        function initBarcodeScanner(readers) {
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#scanner-container'),
                    constraints: {
                        facingMode: "environment"
                    }
                },
                decoder: {
                    readers: readers
                }
            }, function(err) {
                if (err) {
                    console.error("Error initializing QuaggaJS:", err);
                    showScannerMessage("Error initializing barcode scanner. Please try again.", true);
                    stopScanner();
                    return;
                }
                console.log("QuaggaJS initialization finished. Ready to start.");
                Quagga.start();
                showScannerMessage('Scanning for barcode...');
            });

            Quagga.onDetected(function(result) {
                const code = result.codeResult.code;
                console.log("Barcode detected and processed: " + code);
                findAndPopulateBook(code);
                stopScanner();
                scannerModal.style.display = 'none';
            });
        }

        function stopScanner() {
            if (scannerMode === 'member' && qrCodeScanner) {
                qrCodeScanner.clear().catch(error => {
                    console.error("Failed to clear Html5QrcodeScanner. Is it still running?", error);
                });
                qrCodeScanner = null;
            } else if (scannerMode === 'book' && Quagga) {
                Quagga.stop();
            }
        }
    </script>
@endsection
