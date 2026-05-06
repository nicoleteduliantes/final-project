export function buildBreadcrumb(route, auth) {
    const crumbs = [];

    const resolveLabel = (segment) => {
        if (!segment || segment === 'undefined') return 'Organization';

        // 🔥 Org name priority (store → session fallback)
        const orgName =
            auth?.currentOrg?.org_name ||
            sessionStorage.getItem('current_org_name');

        if (route.path.includes('/org/') && orgName) {
            return orgName;
        }

        // 🔥 clean static routes
        const map = {
            apply: 'Application Form',
            dashboard: 'Dashboard',
            discover: 'Discover',
            memberships: 'My Memberships',
            events: 'Events',
        };

        if (map[segment]) return map[segment];

        // 🔥 fallback formatting
        return segment
            .replace(/[-_]/g, ' ')
            .replace(/\b\w/g, (c) => c.toUpperCase());
    };

    for (const r of route.matched) {
        if (r.meta?.breadcrumb === false) continue;
        if (!r.components) continue;

        let label = r.meta?.breadcrumb;

        // dynamic breadcrumb function support
        if (typeof label === 'function') {
            label = label(route);
        }

        if (!label) {
            const segments = route.path.split('/').filter(Boolean);
            const last = segments[segments.length - 1];
            label = resolveLabel(last);
        }

        // 🚨 block raw numeric IDs (e.g. 202601)
        if (/^\d+$/.test(label)) {
            label = 'Organization';
        }

        let resolvedPath = r.path;

        Object.keys(route.params).forEach((key) => {
            resolvedPath = resolvedPath.replace(`:${key}`, route.params[key]);
        });

        crumbs.push({
            label,
            path: resolvedPath,
        });
    }

    return crumbs.filter(
        (c, i, arr) => i === 0 || c.label !== arr[i - 1].label
    );
}
