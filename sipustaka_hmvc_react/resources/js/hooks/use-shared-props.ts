import { usePage } from '@inertiajs/react';

export type UserRole = 'admin' | 'user';

export type AuthUser = {
    id: number;
    name: string;
    email: string;
    role: UserRole;
};

type SharedProps = {
    auth: { user: AuthUser };
    flash: { success?: string | null; error?: string | null };
};

/**
 * Read the auth user, the admin flag, and the flash messages shared by HandleInertiaRequests.
 */
export function useSharedProps() {
    const { auth, flash } = usePage<SharedProps>().props;

    return {
        user: auth.user,
        isAdmin: auth.user?.role === 'admin',
        flash,
    };
}
