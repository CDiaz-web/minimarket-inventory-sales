class HttpManager {

    constructor() {
        this.baseHeaders = {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
    }

    async request(url, options = {}) {

        try {

            const response = await fetch(url, {
                headers: {
                    ...this.baseHeaders,
                    ...(options.headers || {})
                },
                credentials: 'same-origin',
                ...options
            });

            // Sesión expirada (muy útil luego)
            if (response.status === 401) {
                App.alert.error('Sesión expirada');
                window.location.href = '/';
                return;
            }

            const contentType = response.headers.get('content-type');

            let data;

            if (contentType && contentType.includes('application/json')) {
                data = await response.json();
            } else {
                data = await response.text();
            }

            if (!response.ok) {
                throw data;
            }

            return data;

        } catch (error) {

            console.error('HTTP ERROR:', error);

            App.alert.error(
                error?.message || 'Error de comunicación con el servidor'
            );

            throw error;
        }
    }

    // ======================
    // Métodos públicos
    // ======================

    get(url) {
        return this.request(url, {
            method: 'GET'
        });
    }

    post(url, body = {}) {
        return this.request(url, {
            method: 'POST',
            body: JSON.stringify(body)
        });
    }

    put(url, body = {}) {
        return this.request(url, {
            method: 'PUT',
            body: JSON.stringify(body)
        });
    }

    delete(url) {
        return this.request(url, {
            method: 'DELETE'
        });
    }
}

export const http = new HttpManager();