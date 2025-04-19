const mongoose = require('mongoose');

mongoose.connect('mongodb://localhost:27017/UrbanFoodDB', {
    useNewUrlParser: true,
    useUnifiedTopology: true
}).then(() => {
    console.log("Connected to MongoDB!");
}).catch(err => {
    console.error("Connection failed!", err);
});

const reviewSchema = new mongoose.Schema({
    ReviewID: String,
    ProductID: String,
    CustomerID: String,
    Rating: Number,
    Feedback: String,
    ReviewDate: Date
});

const ProductReview = mongoose.model('ProductReview', reviewSchema);

const addReview = async () => {
    const review = new ProductReview({
        ReviewID: "1",
        ProductID: "P123",
        CustomerID: "C456",
        Rating: 5,
        Feedback: "Excellent product, very fresh!",
        ReviewDate: new Date("2025-04-18")
    });

    await review.save();
    console.log("Review saved!");
};

addReview();

const getReviewsByProduct = async (productId) => {
    const reviews = await ProductReview.find({ ProductID: productId });
    console.log("Reviews for ProductID:", productId, reviews);
};

getReviewsByProduct("P123");

const getReviewsByRating = async (minRating) => {
    const reviews = await ProductReview.find({ Rating: { $gte: minRating } });
    console.log("Reviews with Rating >= ", minRating, reviews);
};

getReviewsByRating(4);

const searchReviewsByKeyword = async (keyword) => {
    const reviews = await ProductReview.find({ Feedback: { $regex: keyword, $options: 'i' } });
    console.log("Reviews matching keyword:", keyword, reviews);
};

searchReviewsByKeyword("fresh");

const express = require('express');
const app = express();
app.use(express.json());

// Get reviews by product ID
app.get('/reviews/:productId', async (req, res) => {
    const reviews = await ProductReview.find({ ProductID: req.params.productId });
    res.json(reviews);
});

// Get reviews with minimum rating
app.get('/reviews/rating/:minRating', async (req, res) => {
    const reviews = await ProductReview.find({ Rating: { $gte: req.params.minRating } });
    res.json(reviews);
});

// Search reviews by keyword
app.get('/reviews/search/:keyword', async (req, res) => {
    const reviews = await ProductReview.find({ Feedback: { $regex: req.params.keyword, $options: 'i' } });
    res.json(reviews);
});

app.listen(3000, () => console.log("Server running on port 3000"));
