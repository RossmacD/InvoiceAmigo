// utilities.js
export function formatRateText(item) {
    let formattedText;
    if (item.type === "product") {
        formattedText = "Product";
    } else if (item.rate_unit === "day") {
        formattedText = "Daily";
    } else {
        formattedText =
            item.rate_unit.charAt(0).toUpperCase() +
            item.rate_unit.slice(1) +
            "ly";
    }
    return formattedText;
}