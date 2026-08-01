// SaranIndex.com Main JavaScript File

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search_box');
    const micButton = document.getElementById('micButton');
    const autocompleteResults = document.getElementById('autocomplete_results');

    // Voice Speech Recognition Setup
    if (micButton && searchInput) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

        if (SpeechRecognition) {
            const recognition = new SpeechRecognition();
            recognition.lang = 'hi-IN'; // Hindi / Indian English Support
            recognition.continuous = false;
            recognition.interimResults = false;

            micButton.addEventListener('click', function (e) {
                e.preventDefault();
                micButton.classList.add('listening');
                recognition.start();
            });

            recognition.onresult = function (event) {
                const transcript = event.results[0][0].transcript;
                searchInput.value = transcript;
                micButton.classList.remove('listening');
                // Trigger form search
                if (searchInput.form) {
                    searchInput.form.submit();
                }
            };

            recognition.onerror = function (event) {
                console.error('Speech Recognition Error:', event.error);
                micButton.classList.remove('listening');
            };

            recognition.onend = function () {
                micButton.classList.remove('listening');
            };
        } else {
            micButton.style.display = 'none'; // Hide if browser doesn't support Web Speech API
        }
    }

    // Autocomplete Dropdown Handler
    if (searchInput && autocompleteResults) {
        let debounceTimer;

        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            const query = searchInput.value.trim();

            if (query.length < 2) {
                autocompleteResults.style.display = 'none';
                autocompleteResults.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(function () {
                fetch(`api/search_suggest.php?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            let html = '<ul class="list-group shadow-lg border-0 rounded-4 overflow-hidden mt-1">';
                            data.forEach(item => {
                                html += `
                                    <a href="${item.url}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2 px-3">
                                        <div>
                                            <div class="fw-bold text-dark mb-0">${item.title}</div>
                                            <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>${item.block_name} • ${item.category_name}</small>
                                        </div>
                                        <i class="bi bi-chevron-right text-muted small"></i>
                                    </a>
                                `;
                            });
                            html += '</ul>';
                            autocompleteResults.innerHTML = html;
                            autocompleteResults.style.display = 'block';
                        } else {
                            autocompleteResults.style.display = 'none';
                        }
                    })
                    .catch(err => {
                        console.error('Autocomplete API fetch error:', err);
                        autocompleteResults.style.display = 'none';
                    });
            }, 250);
        });

        // Hide dropdown on outside click
        document.addEventListener('click', function (e) {
            if (!searchInput.contains(e.target) && !autocompleteResults.contains(e.target)) {
                autocompleteResults.style.display = 'none';
            }
        });
    }

    // First Time Visitor Non-Government & Non-Political Disclaimer Modal
    const DISCLAIMER_KEY = 'saranindex_disclaimer_accepted';
    if (!localStorage.getItem(DISCLAIMER_KEY)) {
        const disclaimerModalEl = document.getElementById('disclaimerModal');
        if (disclaimerModalEl && typeof bootstrap !== 'undefined') {
            const disclaimerModal = new bootstrap.Modal(disclaimerModalEl, {
                backdrop: 'static',
                keyboard: false
            });
            disclaimerModal.show();

            const acceptBtn = document.getElementById('acceptDisclaimerBtn');
            if (acceptBtn) {
                acceptBtn.addEventListener('click', function () {
                    localStorage.setItem(DISCLAIMER_KEY, 'true');
                });
            }
        }
    }
});
