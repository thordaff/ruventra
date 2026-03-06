export interface Artist {
    name: string;
    role: string;
}

export interface Event {
    id: number;
    title: string;
    thumbnail: string;
    date: string;
    location: string;
    ticketsSold: number;
    capacity: number;
    revenue: number;
    category: string;
    description: string;
    lineup: Artist[];
}
