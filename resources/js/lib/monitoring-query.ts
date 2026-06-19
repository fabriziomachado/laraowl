import type { RouteQueryOptions } from '@/wayfinder';

export type MonitoringQuery = {
    period?: string | null;
    from?: string | null;
    to?: string | null;
};

export function monitoringQuery({
    period,
    from,
    to,
}: MonitoringQuery): RouteQueryOptions {
    return {
        query: {
            period,
            from,
            to,
        },
    };
}

export function appendMonitoringQuery(
    url: string,
    { period, from, to }: MonitoringQuery,
): string {
    const params = new URLSearchParams();

    if (period) {
        params.set('period', period);
    }

    if (from) {
        params.set('from', from);
    }

    if (to) {
        params.set('to', to);
    }

    const query = params.toString();

    return query ? `${url}?${query}` : url;
}
