import { API_BASE_URL } from '@/config/api';

async function request(endpoint, method, data = null) {
    const res = await fetch(`${API_BASE_URL}${endpoint}`, {
        method,
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            Authorization: `Bearer ${localStorage.getItem('AUTH_TOKEN')}`,
        },
        body: data ? JSON.stringify(data) : null,
    });

    return await res.json();
}

export const get = (endpoint) => request(endpoint, 'GET');

export const post = (endpoint, data) => request(endpoint, 'POST', data);

export const put = (endpoint, data) => request(endpoint, 'PUT', data);

export const del = (endpoint) => request(endpoint, 'DELETE');
