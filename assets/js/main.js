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
                            let html = '<div class="list-group shadow-lg border rounded-4 overflow-auto dropdown-menu-suggest mt-1" style="max-height: 380px;">';
                            data.forEach(item => {
                                const subTitle = item.hindi_title ? `<span class="text-muted font-hindi fw-normal small ms-1">(${item.hindi_title})</span>` : '';
                                html += `
                                    <a href="${item.url}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3 px-3.5 border-bottom">
                                        <div class="pe-3 min-w-0">
                                            <div class="fw-bold text-dark mb-1 font-heading" style="font-size: 0.95rem;">
                                                ${item.title}${subTitle}
                                            </div>
                                            <div class="d-flex align-items-center gap-2 flex-wrap text-muted small">
                                                <span class="badge bg-primary-subtle text-primary fw-semibold px-2 py-0.5 rounded-pill" style="font-size: 0.75rem;">
                                                    <i class="bi bi-geo-alt-fill me-1"></i>${item.block_name} Block
                                                </span>
                                                <span class="badge bg-light text-secondary border px-2 py-0.5 rounded-pill" style="font-size: 0.75rem;">
                                                    <i class="bi bi-tag-fill me-1"></i>${item.category_name}
                                                </span>
                                            </div>
                                        </div>
                                        <i class="bi bi-chevron-right text-primary flex-shrink-0 fs-6"></i>
                                    </a>
                                `;
                            });
                            html += '</div>';
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

    // Back to Top Button Controller
    const backToTopBtn = document.getElementById('backToTopBtn');
    if (backToTopBtn) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        });

        backToTopBtn.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});

