import React from 'react';
import './cart.css'; 
const Cart = ({ cartItems, removeItem }) => {
  return (
    <div className="cart-container">
      <h2>Shopping Cart</h2>
      {cartItems.length === 0 ? (
        <p>Your cart is empty.</p>
      ) : (
        <ul>
          {cartItems.map((item) => (
            <li key={item.id} className="cart-item">
              <p>Name: {item.emp_name}</p>
              <p>Email: {item.emp_email}</p>
              <p>Message: {item.emp_dept}</p>
              <button onClick={() => removeItem(item.id)}>Remove</button>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
};

export default Cart;
