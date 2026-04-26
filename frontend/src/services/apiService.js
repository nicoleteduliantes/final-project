import { API_BASE_URL } from '@/config/api';

export async function post(endpoint, data) {
    const res = await fetch(`${API_BASE_URL}${endpoint}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json', //tells Laravel to talk in JSON
            'Authorization': `Bearer ${localStorage.getItem('AUTH_TOKEN')}` // sends "key" once a user logs in
        },
        body: JSON.stringify(data),
    });

    return res.json();
}

export async function get(endpoint) {
const res = await fetch(`${API_BASE_URL}${endpoint}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('AUTH_TOKEN')}`
        }
    });    return res.json();
}

export async function put(endpoint, data) {
    const res = await fetch(`${API_BASE_URL}${endpoint}`, {
        method: 'PUT', // The key difference!
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('AUTH_TOKEN')}`
        },
        body: JSON.stringify(data),
    });


    return res.json();
}
