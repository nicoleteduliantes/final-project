export function buildBreadcrumb(route, metaResolver) {
    const matched = route.matched;

    return matched
        .map((r) => {
            let label = r.meta?.breadcrumb;

            // function-based breadcrumb support
            if (typeof label === 'function') {
                label = label(route);
            }

            // fallback to resolver (dynamic names like org/event)
            if (!label) {
                const segments = route.path.split('/').filter(Boolean);
                const last = segments[segments.length - 1];

                label = metaResolver?.(last, route) || '';
            }

            return {
                label,
                path: r.path,
            };
        })
        .filter((c) => c.label);
}
