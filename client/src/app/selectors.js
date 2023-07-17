export function getUserToken(store) {
    return store.consumer.user;
}

export function getPlayersStats(store) {
    return store.playerStats;
}

export function isLoading(store)
{
    return store.loading.isLoading;
}