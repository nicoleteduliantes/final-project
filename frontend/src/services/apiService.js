import { API_BASE_URL } from '@/config/api';

async function request(endpoint, method, data = null) {
    const options = {
        method,
        headers: {
            Accept: 'application/json',
            Authorization: `Bearer ${localStorage.getItem('AUTH_TOKEN')}`,
        },
    };

    if (data) {
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(data);
    }

    const res = await fetch(`${API_BASE_URL}${endpoint}`, options);

    if (!res.ok) {
        const errorData = await res.json().catch(() => ({}));
        throw new Error(errorData.message || `Server error: ${res.status}`);
    }

    const contentType = res.headers.get('content-type');
    if (!contentType || !contentType.includes('application/json')) {
        return null;
    }

    return await res.json();
}

export const get = (endpoint) => request(endpoint, 'GET');

export const post = (endpoint, data) => request(endpoint, 'POST', data);

export const put = (endpoint, data) => request(endpoint, 'PUT', data);

export const del = (endpoint) => request(endpoint, 'DELETE');
