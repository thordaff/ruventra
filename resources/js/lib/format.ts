/**
 * Format a number as Indonesian Rupiah — full form.
 * e.g. 180000000 → "Rp 180.000.000"
 */
export function formatRp(val: number): string {
    return 'Rp ' + val.toLocaleString('id-ID');
}

/**
 * Format a number as Indonesian Rupiah with automatic scale suffix.
 * e.g. 1_800_000_000 → "Rp 1,8 miliar"
 *      420_000_000   → "Rp 420 juta"
 *      850_000       → "Rp 850 ribu"
 *      750           → "Rp 750"
 */
export function formatRpShort(val: number): string {
    if (val >= 1_000_000_000_000)
        return `Rp ${(val / 1_000_000_000_000).toLocaleString('id-ID', { maximumFractionDigits: 2 })} triliun`;
    if (val >= 1_000_000_000)
        return `Rp ${(val / 1_000_000_000).toLocaleString('id-ID', { maximumFractionDigits: 2 })} miliar`;
    if (val >= 1_000_000)
        return `Rp ${(val / 1_000_000).toLocaleString('id-ID', { maximumFractionDigits: 2 })} juta`;
    if (val >= 1_000)
        return `Rp ${(val / 1_000).toLocaleString('id-ID', { maximumFractionDigits: 1 })} ribu`;
    return `Rp ${val.toLocaleString('id-ID')}`;
}

/**
 * Format an ISO date string to a long Indonesian date.
 * e.g. "2026-02-01" → "1 Februari 2026"
 */
export function formatDate(dateStr: string): string {
    return new Date(dateStr).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
}
