
/**
 * Converts string into human readable string
 * 
 * example: 'PUBLIC_OPEN' -> 'Public open'
 * 
 * @param {string} string 
 */
export function forHumans(string){
    return this.capitalizeFirstLetter(string.replace("_", " ").toLowerCase()) 
}


export function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}