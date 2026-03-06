/**
 * Calculate ticket occupancy as a whole-number percentage.
 * e.g. occupancyPercent(1200, 1500) → 80
 */
export function occupancyPercent(sold: number, capacity: number): number {
    return Math.round((sold / capacity) * 100);
}

/**
 * Tailwind class map for event category badges.
 * Falls back to gray if the category is unknown.
 */
export const categoryColor: Record<string, string> = {
    Musik: 'bg-indigo-100 text-indigo-700',
    Teknologi: 'bg-sky-100 text-sky-700',
    Pameran: 'bg-violet-100 text-violet-700',
    Kuliner: 'bg-orange-100 text-orange-700',
};

export function getCategoryColor(category: string): string {
    return categoryColor[category] ?? 'bg-gray-100 text-gray-600';
}
