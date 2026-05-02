export function buildBreadcrumb(route, metaResolver) {
    const crumbs = [];

    for (const r of route.matched) {
        // skip hidden breadcrumbs (like layout or redirects)
        if (r.meta?.breadcrumb === false) continue;

        // ignore invalid route records
        if (!r.components) continue;

        let label = r.meta?.breadcrumb;

        // dynamic breadcrumb
        if (typeof label === 'function') {
            label = label(route);
        }

        // fallback label
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

    // remove duplicate labels (e.g. "Dashboard / Dashboard")
    return crumbs.filter((c, i, arr) => {
        return i === 0 || c.label !== arr[i - 1].label;
    });
}
