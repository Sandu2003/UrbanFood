{
    "ReviewID": "1",
    "ProductID": "P123",
    "CustomerID": "C456",
    "Rating": 4,
    "Feedback": "The apples are fresh and tasty!",
    "ReviewDate": "2025-04-18"
  }
  db.ProductReviews.insertMany([
    {
        "ReviewID": "1",
        "ProductID": "P123",
        "CustomerID": "C456",
        "Rating": 4,
        "Feedback": "The apples are fresh and tasty!",
        "ReviewDate": "2025-04-18"
    },
    {
        "ReviewID": "2",
        "ProductID": "P124",
        "CustomerID": "C789",
        "Rating": 5,
        "Feedback": "Excellent quality and fast delivery!",
        "ReviewDate": "2025-04-19"
    }
]);
db.ProductReviews.find({ "ProductID": "P123" });
db.ProductReviews.find({ "Rating": { $gte: 4 } });
db.ProductReviews.countDocuments({ "ProductID": "P123" });
db.ProductReviews.find({ "Feedback": { $regex: "fresh", $options: "i" } });
  