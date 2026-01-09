/**
 * API Handler for E-Gov Project
 * Common functions for making API calls
 */

const API_BASE_URL = 'api/';

/**
 * Fetch notices from API
 * @param {number} page - Page number
 * @param {number} limit - Items per page
 * @param {string} category - Filter by category
 * @returns {Promise}
 */
async function fetchNotices(page = 1, limit = 10, category = '') {
    try {
        let url = `${API_BASE_URL}notices.php?page=${page}&limit=${limit}`;
        if (category) {
            url += `&category=${encodeURIComponent(category)}`;
        }
        
        const response = await fetch(url);
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error fetching notices:', error);
        return { success: false, message: 'Failed to fetch notices' };
    }
}

/**
 * Fetch applications from API
 * @returns {Promise}
 */
async function fetchApplications() {
    try {
        const response = await fetch(`${API_BASE_URL}applications.php`);
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error fetching applications:', error);
        return { success: false, message: 'Failed to fetch applications' };
    }
}

/**
 * Submit job application
 * @param {FormData} formData - Application form data
 * @returns {Promise}
 */
async function submitApplication(formData) {
    try {
        const response = await fetch(`${API_BASE_URL}submit_application.php`, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error submitting application:', error);
        return { success: false, message: 'Failed to submit application' };
    }
}

/**
 * Submit contact form
 * @param {Object} data - Contact form data
 * @returns {Promise}
 */
async function submitContactForm(data) {
    try {
        const response = await fetch(`${API_BASE_URL}contact.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error submitting contact form:', error);
        return { success: false, message: 'Failed to submit message' };
    }
}

/**
 * Fetch exam schedules from API
 * @returns {Promise}
 */
async function fetchExamSchedules() {
    try {
        const response = await fetch(`${API_BASE_URL}exams.php`);
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error fetching exam schedules:', error);
        return { success: false, message: 'Failed to fetch exam schedules' };
    }
}

/**
 * Fetch results from API
 * @param {number} page - Page number
 * @param {number} limit - Items per page
 * @returns {Promise}
 */
async function fetchResults(page = 1, limit = 10) {
    try {
        const response = await fetch(`${API_BASE_URL}results.php?page=${page}&limit=${limit}`);
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error fetching results:', error);
        return { success: false, message: 'Failed to fetch results' };
    }
}

/**
 * Fetch publications from API
 * @param {number} page - Page number
 * @param {number} limit - Items per page
 * @param {string} category - Filter by category
 * @returns {Promise}
 */
async function fetchPublications(page = 1, limit = 10, category = '') {
    try {
        let url = `${API_BASE_URL}publications.php?page=${page}&limit=${limit}`;
        if (category) {
            url += `&category=${encodeURIComponent(category)}`;
        }
        
        const response = await fetch(url);
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error fetching publications:', error);
        return { success: false, message: 'Failed to fetch publications' };
    }
}

/**
 * Format date in Nepali format
 * @param {string} dateString - Date string
 * @returns {string}
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('ne-NP', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

/**
 * Show loading spinner
 * @param {string} elementId - Element ID
 */
function showLoading(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">लोड हुँदैछ...</span></div></div>';
    }
}

/**
 * Show error message
 * @param {string} elementId - Element ID
 * @param {string} message - Error message
 */
function showError(elementId, message) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = `<div class="alert alert-danger">${message}</div>`;
    }
}

/**
 * Show success message
 * @param {string} elementId - Element ID
 * @param {string} message - Success message
 */
function showSuccess(elementId, message) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = `<div class="alert alert-success">${message}</div>`;
    }
}
