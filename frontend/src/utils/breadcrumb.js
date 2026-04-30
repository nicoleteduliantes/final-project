export function buildBreadcrumb(route, metaResolver) {
    const crumbs = [];

    for (const r of route.matched) {
        // ignore layout / empty records
        if (!r.components) continue;

        let label = r.meta?.breadcrumb;

        if (typeof label === 'function') {
            label = label(route);
        }

        // fallback
        if (!label) {
            const segments = route.path.split('/').filter(Boolean);
            const last = segments[segments.length - 1];

            label = metaResolver?.(last, route) || last;
        }

        crumbs.push({
            label,
            path: r.path,
        });
    }

    // INAL FIX: remove duplicates like "Dashboard / Dashboard"
    const cleaned = [];

    for (const c of crumbs) {
        const last = cleaned[cleaned.length - 1];

        if (!last || last.label !== c.label) {
            cleaned.push(c);
        }
    }

    return cleaned;
}
