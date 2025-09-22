# M-Pesa API Documentation

This document provides comprehensive information about the M-Pesa API endpoints and their usage.

## Table of Contents

1. [C2B Payment Single Stage](#c2b-payment-single-stage)
2. [B2C Payment](#b2c-payment)
3. [B2B Payment](#b2b-payment)
4. [Query Transaction Status](#query-transaction-status)
5. [Reversal API](#reversal-api)

---

## C2B Payment Single Stage

**Customer-to-Business Transaction**

The C2B API Call is used as a standard customer-to-business transaction. Funds from the customer's mobile money wallet will be deducted and transferred to the mobile money wallet of the business. To authenticate and authorize this transaction, M-Pesa Payments Gateway will initiate a USSD Push message to the customer to gather and verify the mobile money PIN number.

### Endpoint Information

| Property | Value |
|----------|-------|
| **Address** | `api.sandbox.vm.co.mz` |
| **Port** | `18352` |
| **Path** | `/ipg/v1x/c2bPayment/singleStage/` |
| **SSL** | ✔ Required |
| **Method** | `POST` |

### Headers

| Header | Value | Required |
|--------|-------|----------|
| `Content-Type` | `application/json` | ✔ |
| `Authorization` | `Bearer {access_token}` | ✔ |
| `Origin` | `developer.mpesa.vm.co.mz` | ✔ |

### Request Parameters

| Parameter | Description | Type | Required | Example |
|-----------|-------------|------|----------|---------|
| `input_TransactionReference` | Transaction reference for customer/business | String | ✔ | `T12344C` |
| `input_CustomerMSISDN` | Customer's mobile number | String | ✔ | `258843330333` |
| `input_Amount` | Transaction amount | String | ✔ | `10` |
| `input_ThirdPartyReference` | Unique third party system reference | String | ✔ | `11114` |
| `input_ServiceProviderCode` | Business shortcode for funds credit | String | ✔ | `171717` |

### Response Parameters

#### Synchronous Response

| Field | Description | Type | Example |
|-------|-------------|------|---------|
| `output_ConversationID` | Mobile Money platform generated ID | String | `AG_20180206_00005e7dccc6da08efa8` |
| `output_TransactionID` | Mobile Money platform transaction ID | String | `4XDF12345` |
| `output_ResponseDesc` | iPG platform status description | String | See response codes |
| `output_ResponseCode` | iPG platform status code | String | See response codes |
| `output_ThirdPartyReference` | Third party system reference | String | `11114` |

#### Asynchronous Response

Immediate response provided before session closure. Use Conversation ID for status queries.

| Field | Description | Type | Example |
|-------|-------------|------|---------|
| `output_ThirdPartyReference` | Third party system reference | String | `11114` |
| `output_ConversationID` | Mobile Money platform generated ID | String | `18ffa4ccf93343379421d2eac15a3e8c` |
| `output_ResponseCode` | iPG platform status code | String | See response codes |
| `output_ResponseDesc` | iPG platform status description | String | See response codes |

#### Async Result Callback (Webhook)

Sent to your configured Response URL when transaction completes.

**Request to Your Endpoint:**

| Field | Description | Type | Example |
|-------|-------------|------|---------|
| `input_OriginalConversationID` | iPG platform conversation ID | String | `241fasif1f1n92fn129nfasnf91nf1` |
| `input_ThirdPartyReference` | Third party system reference | String | `11114` |
| `input_TransactionID` | Mobile Money transaction ID | String | `4XDF12345` |
| `input_ResultCode` | Transaction result code | String | `0` |
| `input_ResultDesc` | Transaction result description | String | `Request Processed Successfully` |

**Required Response from Your Endpoint:**

| Field | Description | Type | Example |
|-------|-------------|------|---------|
| `output_OriginalConversationID` | Same as received conversation ID | String | `241fasif1f1n92fn129nfasnf91nf1` |
| `output_ResponseDesc` | Receipt status description | String | `Successfully Accepted Result` |
| `output_ResponseCode` | Receipt status code | String | `0` |
| `output_ThirdPartyConversationID` | Your system reference | String | `11114` |

---

## B2C Payment

**Business-to-Customer Transaction**

The B2C API Call is used as a standard business-to-customer transaction. Funds from the business' mobile money wallet will be deducted and transferred to the mobile money wallet of the third party customer.

### Endpoint Information

| Property | Value |
|----------|-------|
| **Address** | `api.sandbox.vm.co.mz` |
| **Port** | `18345` |
| **Path** | `/ipg/v1x/b2cPayment/` |
| **SSL** | ✔ Required |
| **Method** | `POST` |

### Headers

| Header | Value | Required |
|--------|-------|----------|
| `Content-Type` | `application/json` | ✔ |
| `Authorization` | `Bearer {access_token}` | ✔ |
| `Origin` | `developer.mpesa.vm.co.mz` | ✔ |

### Request Parameters

| Parameter | Description | Type | Required | Example |
|-----------|-------------|------|----------|---------|
| `input_TransactionReference` | Transaction reference for customer/business | String | ✔ | `T12344C` |
| `input_CustomerMSISDN` | Customer's mobile number | String | ✔ | `258843330333` |
| `input_Amount` | Transaction amount | String | ✔ | `10` |
| `input_ThirdPartyReference` | Unique third party system reference | String | ✔ | `11114` |
| `input_ServiceProviderCode` | Business shortcode for funds debit | String | ✔ | `171717` |

### Response Parameters

#### Synchronous Response

| Field | Description | Type | Example |
|-------|-------------|------|---------|
| `output_ConversationID` | Mobile Money platform generated ID | String | `AG_20180206_00005e7dccc6da08efa8` |
| `output_TransactionID` | Mobile Money platform transaction ID | String | `4XDF12345` |
| `output_ResponseDesc` | iPG platform status description | String | See response codes |
| `output_ResponseCode` | iPG platform status code | String | See response codes |
| `output_ThirdPartyReference` | Third party system reference | String | `11114` |

#### Asynchronous Response

Immediate response provided before session closure. Use Conversation ID for status queries.

| Field | Description | Type | Example |
|-------|-------------|------|---------|
| `output_ThirdPartyReference` | Third party system reference | String | `11114` |
| `output_ConversationID` | Mobile Money platform generated ID | String | `18ffa4ccf93343379421d2eac15a3e8c` |
| `output_ResponseCode` | iPG platform status code | String | See response codes |
| `output_ResponseDesc` | iPG platform status description | String | See response codes |

#### Async Result Callback (Webhook)

**Request to Your Endpoint:**

| Field | Description | Type | Example |
|-------|-------------|------|---------|
| `input_OriginalConversationID` | iPG platform conversation ID | String | `241fasif1f1n92fn129nfasnf91nf1` |
| `input_ThirdPartyReference` | Third party system reference | String | `11114` |
| `input_TransactionID` | Mobile Money transaction ID | String | `4XDF12345` |
| `input_ResultCode` | Transaction result code | String | `0` |
| `input_ResultDesc` | Transaction result description | String | `Request Processed Successfully` |

**Required Response from Your Endpoint:**

| Field | Description | Type | Example |
|-------|-------------|------|---------|
| `output_OriginalConversationID` | Same as received conversation ID | String | `241fasif1f1n92fn129nfasnf91nf1` |
| `output_ResponseDesc` | Receipt status description | String | `Successfully Accepted Result` |
| `output_ResponseCode` | Receipt status code | String | `0` |
| `output_ThirdPartyConversationID` | Your system reference | String | `11114` |

---

## B2B Payment

**Business-to-Business Transaction**

The B2B API Call is used as a standard business-to-business transaction. Funds from the business' mobile money wallet will be deducted and transferred to the mobile money wallet of the third party business.

### Endpoint Information

| Property | Value |
|----------|-------|
| **Address** | `api.sandbox.vm.co.mz` |
| **Port** | `18349` |
| **Path** | `/ipg/v1x/b2bPayment/` |
| **SSL** | ✔ Required |
| **Method** | `POST` |

### Headers

| Header | Value | Required |
|--------|-------|----------|
| `Content-Type` | `application/json` | ✔ |
| `Authorization` | `Bearer {access_token}` | ✔ |
| `Origin` | `developer.mpesa.vm.co.mz` | ✔ |

### Request Parameters

| Parameter | Description | Type | Required | Example |
|-----------|-------------|------|----------|---------|
| `input_TransactionReference` | Transaction reference for business | String | ✔ | `T12344C` |
| `input_Amount` | Transaction amount | String | ✔ | `10` |
| `input_ThirdPartyReference` | Unique third party system reference | String | ✔ | `11114` |
| `input_PrimaryPartyCode` | Business shortcode for funds debit | String | ✔ | `171717` |
| `input_ReceiverPartyCode` | Business shortcode for funds credit | String | ✔ | `979797` |

### Response Parameters

#### Synchronous Response

| Field | Description | Type | Example |
|-------|-------------|------|---------|
| `output_ConversationID` | Mobile Money platform generated ID | String | `AG_20180206_00005e7dccc6da08efa8` |
| `output_TransactionID` | Mobile Money platform transaction ID | String | `4XDF12345` |
| `output_ResponseDesc` | iPG platform status description | String | See response codes |
| `output_ResponseCode` | iPG platform status code | String | See response codes |
| `output_ThirdPartyReference` | Third party system reference | String | `11114` |

#### Asynchronous Response

| Field | Description | Type | Example |
|-------|-------------|------|---------|
| `output_ThirdPartyReference` | Third party system reference | String | `11114` |
| `output_ConversationID` | Mobile Money platform generated ID | String | `18ffa4ccf93343379421d2eac15a3e8c` |
| `output_ResponseCode` | iPG platform status code | String | See response codes |
| `output_ResponseDesc` | iPG platform status description | String | See response codes |

---

## Query Transaction Status

**Transaction Status Inquiry**

The Query Transaction Status API is used to determine the current status of a particular transaction. Using either the Transaction ID or the Conversation ID from the Mobile Money Platform, the M-Pesa Payments Gateway will return information about the transaction's status.

### Endpoint Information

| Property | Value |
|----------|-------|
| **Address** | `api.sandbox.vm.co.mz` |
| **Port** | `18353` |
| **Path** | `/ipg/v1x/queryTransactionStatus/` |
| **SSL** | ✔ Required |
| **Method** | `GET` |

### Headers

| Header | Value | Required |
|--------|-------|----------|
| `Content-Type` | `application/json` | ✔ |
| `Authorization` | `Bearer {access_token}` | ✔ |
| `Origin` | `developer.mpesa.vm.co.mz` | ✔ |

### Request Parameters

| Parameter | Description | Type | Required | Example |
|-----------|-------------|------|----------|---------|
| `input_ThirdPartyReference` | Unique third party system reference | String | ✔ | `11114` |
| `input_QueryReference` | Transaction ID, ThirdPartyReference, or ConversationID | String | ✔ | `5C1400CVRO` or `AG_20180206_00005e7dccc6da08efa8` |
| `input_ServiceProviderCode` | Business shortcode | String | ✔ | `171717` |

### Response Parameters

#### Synchronous Response

| Field | Description | Type | Example |
|-------|-------------|------|---------|
| `output_ConversationID` | Mobile Money platform generated ID | String | `AG_20180206_00005e7dccc6da08efa8` |
| `output_ResponseDesc` | iPG platform status description | String | See response codes |
| `output_ResponseCode` | iPG platform status code | String | See response codes |
| `output_ThirdPartyReference` | Third party system reference | String | `11114` |
| `output_ResponseTransactionStatus` | Transaction processing status | String | `Cancelled`, `Completed`, `Expired`, `N/A` |

---

## Reversal API

**Transaction Reversal**

The Reversal API is used to reverse a successful transaction. Using the Transaction ID of a previously successful transaction, M-Pesa Payments Gateway will withdraw the funds from the recipient party's mobile money wallet and revert the funds to the mobile money wallet of the initiating party of the original transaction.

### Endpoint Information

| Property | Value |
|----------|-------|
| **Address** | `api.sandbox.vm.co.mz` |
| **Port** | `18354` |
| **Path** | `/ipg/v1x/reversal/` |
| **SSL** | ✔ Required |
| **Method** | `PUT` |

### Headers

| Header | Value | Required |
|--------|-------|----------|
| `Content-Type` | `application/json` | ✔ |
| `Authorization` | `Bearer {access_token}` | ✔ |
| `Origin` | `developer.mpesa.vm.co.mz` | ✔ |

### Request Parameters

| Parameter | Description | Type | Required | Example |
|-----------|-------------|------|----------|---------|
| `input_TransactionID` | Mobile Money Platform TransactionID for successful transaction | String | ✔ | `49XCDF6` |
| `input_SecurityCredential` | Vodacom generated security credential | String | ✔ | `Mpesa2019` |
| `input_InitiatorIdentifier` | Vodacom generated initiator identifier | String | ✔ | `MPesa2018` |
| `input_ThirdPartyReference` | Unique third party system reference | String | ✔ | `11114` |
| `input_ServiceProviderCode` | Business shortcode | String | ✔ | `171717` |
| `input_ReversalAmount` | Amount to reverse (optional for partial reversal) | String | ✘ | `10` |

### Response Parameters

#### Synchronous Response

| Field | Description | Type | Example |
|-------|-------------|------|---------|
| `output_ConversationID` | Mobile Money platform generated ID | String | `AG_20180206_00005e7dccc6da08efa8` |
| `output_TransactionID` | Mobile Money platform transaction ID | String | `4XDF12345` |
| `output_ResponseDesc` | iPG platform status description | String | See response codes |
| `output_ResponseCode` | iPG platform status code | String | See response codes |
| `output_ThirdPartyReference` | Third party system reference | String | `11114` |

#### Asynchronous Response

| Field | Description | Type | Example |
|-------|-------------|------|---------|
| `output_ThirdPartyReference` | Third party system reference | String | `11114` |
| `output_ConversationID` | Mobile Money platform generated ID | String | `18ffa4ccf93343379421d2eac15a3e8c` |
| `output_ResponseCode` | iPG platform status code | String | See response codes |
| `output_ResponseDesc` | iPG platform status description | String | See response codes |

#### Async Result Callback (Webhook)

**Request to Your Endpoint:**

| Field | Description | Type | Example |
|-------|-------------|------|---------|
| `input_OriginalConversationID` | iPG platform conversation ID | String | `241fasif1f1n92fn129nfasnf91nf1` |
| `input_ThirdPartyReference` | Third party system reference | String | `11114` |
| `input_TransactionID` | Mobile Money transaction ID | String | `4XDF12345` |
| `input_ResultCode` | Transaction result code | String | `0` |
| `input_ResultDesc` | Transaction result description | String | `Request Processed Successfully` |

**Required Response from Your Endpoint:**

| Field | Description | Type | Example |
|-------|-------------|------|---------|
| `output_OriginalConversationID` | Same as received conversation ID | String | `241fasif1f1n92fn129nfasnf91nf1` |
| `output_ResponseDesc` | Receipt status description | String | `Successfully Accepted Result` |
| `output_ResponseCode` | Receipt status code | String | `0` |
| `output_ThirdPartyConversationID` | Your system reference | String | `11114` |

---

## Response Codes

### Common Response Codes

| Code | Description | Status |
|------|-------------|--------|
| `INS-0` | Request processed successfully | Success |
| `INS-1` | Internal Error | Error |
| `INS-2` | Not enough balance | Error |
| `INS-4` | Transaction failed | Error |
| `INS-5` | Transaction expired | Error |
| `INS-6` | Transaction not permitted to sender | Error |
| `INS-9` | Request timeout | Error |
| `INS-10` | Duplicate transaction | Error |

### Transaction Status Values

| Status | Description |
|--------|-------------|
| `Completed` | Transaction completed successfully |
| `Pending` | Transaction is being processed |
| `Cancelled` | Transaction was cancelled |
| `Expired` | Transaction expired |
| `Failed` | Transaction failed |
| `N/A` | Status not available |

---

## Authentication

All API calls require authentication using a Bearer token. The token should be obtained from the M-Pesa authentication endpoint and included in the `Authorization` header of all requests.

### Example Authentication Header

```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...
```

### Token Expiration

Tokens have a limited lifespan and should be refreshed when they expire. Monitor the response codes for authentication failures and implement token refresh logic accordingly.



### Response Codes & Descriptions

| Http Status Code	| Code	| Description |
|-------------------|-------|-------------|
| 200 / 201	| INS-0	| Request processed successfully |
| 500	| INS-1	| Internal Error |
| 401	| INS-2	| Invalid API Key |
| 401	| INS-4	| User is not active |
| 401	| INS-5	| Transaction cancelled by customer |
| 401	| INS-6	| Transaction Failed |
| 408	| INS-9	| Request timeout |
| 409	| INS-10	| Duplicate Transaction |
| 400	| INS-13	| Invalid Shortcode Used |
| 400	| INS-14	| Invalid Reference Used |
| 400	| INS-15	| Invalid Amount Used |
| 503	| INS-16	| Unable to handle the request due to a temporary overloading |
| 400	| INS-17	| Invalid Transaction Reference. Length Should Be Between 1 and 20. |
| 400	| INS-18	| Invalid TransactionID Used |
| 400	| INS-19	| Invalid ThirdPartyReference Used |
| 400	| INS-20	| Not All Parameters Provided. Please try again. |
| 400	| INS-21	| Parameter validations failed. Please try again. |
| 400	| INS-22	| Invalid Operation Type |
| 400	| INS-23	| Unknown Status. Contact M-Pesa Support |
| 400	| INS-24	| Invalid InitiatorIdentifier Used |
| 400	| INS-25	| Invalid SecurityCredential Used |
| 400	| INS-26	| Not authorized |
| 400	| INS-993	| Direct Debit Missing |
| 400	| INS-994	| Direct Debit Already Exists |
| 400	| INS-995	| Customer's Profile Has Problems |
| 400	| INS-996	| Customer Account Status Not Active |
| 400	| INS-997	| Linking Transaction Not Found |
| 400	| INS-998	| Invalid Market |
| 400	| INS-2001	| Initiator authentication error. |
| 400	| INS-2002	| Receiver invalid. |
| 422	| INS-2006	| Insufficient balance |
| 400	| INS-2051	| MSISDN invalid. |
| 400	| INS-2057	| Language code invalid. |