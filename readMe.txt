I have tried to build a coupon management system

The service enables creation of a coupon, updation of existing coupon, application of a coupon by some user

Types of coupons that can be created:
1. One time use coupons ("single-use")
2. Single use coupon per user ("single-use-per-user")
3. Multi use coupons which can be used 'n' number of times ("multi-use")
4. Unlimited use coupons ("perpetual-use")

While creating a coupon one needs to send following information in the request
couponType (any of the 4 specified above)
redemptionsLeft - It is the value of 'n' for 'multi-use' coupon
validUpto (if we want a expiry date for coupon. Need not send it otherwise)

couponCode and couponID are returned as a response to create API call. couponCode is generated at backend

API Calls

1. Creation of coupon:
API call
<dir>/couponManagementSystem/api.php/api/create
(POST)
Payload: {"couponType": "single-use", "validUpto": "2016-11-19"}
Response: {"couponID": 3443, "couponCode" : "xyYndmeY"}

2. Update coupon:
API call
<dir>/couponManagementSystem/api.php/api/update
(PUT)
Payload: {"couponID": 3443, "validUpto": "2016-12-19"}
Response: {}

3. Apply coupon:
API call
<dir>/couponManagementSystem/api.php/api/apply
(POST)
Payload: {"couponCode": "xyYndmeY", "userID": 5}
Response: {}


For single-use-per-coupon, user needs authentication. I am using basic auth with username corresponding to id from User Table and password as auth from User Table. Also userID is mandatory in case of single-use-coupon
If it is not a single-use-per-user coupon then authentication is not mandatory and will be done if Basic Auth is present.

Coupon Transactions are stored in CouponTransaction table. UsedID is stored against the couponID applied and for guests userID is set to 0.

Appropriate response is sent in case of success, authentication failure, bad request, etc.