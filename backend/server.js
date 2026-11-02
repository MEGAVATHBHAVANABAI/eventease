const express = require("express");
const cors = require("cors");
const app = express();
app.use(cors());
app.use(express.json());
let orders = [];
app.post("/order", (req, res) => {
    const order = req.body;
    orders.push(order);
    console.log("New Order Received:");
    console.log(order);
    res.json({ message: "Order saved successfully" });
});
app.get("/orders", (req, res) => {
    res.json(orders);
});
app.listen(5000, () => {
    console.log("Server running at http://localhost:5000");
});