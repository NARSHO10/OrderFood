function addToCart(itemName, itemPrice) {
    console.log('addToCart called:', itemName, itemPrice);
    fetch('/FoodOrderingApp/public/add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name: itemName, price: itemPrice, quantity: 1 })
    })
    .then(async response => {
        const text = await response.text();
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('Non-JSON response from add_to_cart.php:', text);
            throw e;
        }
    })
    .then(data => {
        console.log('add_to_cart response:', data);
        if (data.success) {
            alert('Item added to cart!');
        } else {
            alert('Failed to add item to cart. Please log in.');
        }
    })
    .catch(error => console.error('addToCart error:', error));
}

function removeFromCart(itemName) {
    console.log('removeFromCart called:', itemName);
    fetch('/FoodOrderingApp/public/remove_from_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name: itemName })
    })
    .then(async response => {
        const text = await response.text();
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('Non-JSON response from remove_from_cart.php:', text);
            throw e;
        }
    })
    .then(data => {
        console.log('remove_from_cart response:', data);
        if (data.success) {
            alert('Item removed from cart!');
            location.reload();
        } else {
            alert('Failed to remove item from cart.');
        }
    })
    .catch(error => console.error('removeFromCart error:', error));
}
