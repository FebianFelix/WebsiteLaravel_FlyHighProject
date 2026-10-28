# FlyHigh REST API v1

Base: `/api/v1`

## Public read

- `GET /flights?origin=Jakarta&destination=Bali`
- `GET /flights/{flightId}`

## Bearer-authenticated endpoints

Generate a personal access token at `/account`. Send:

```http
Authorization: Bearer YOUR_TOKEN
Accept: application/json
```

- `GET /me` — account summary
- `GET /bookings` — own bookings (requires `bookings:read`)
- `GET /bookings/{id}` — own booking (requires `bookings:read`)
- `POST /bookings` — make demo hold (requires `bookings:write`)
- `PATCH /bookings/{id}/cancel` — cancel own reservation (requires `bookings:write`)

### Create reservation

```json
{
  "flight_id": 1,
  "request_key": "3db031e1-2b38-43c3-a884-e67ef4b69b91",
  "passengers": ["Example Passenger"]
}
```

The `request_key` UUID can be reused to retry safely. A repeated request
returns the existing booking for the same user and flight. Holds expire
after 15 minutes by default. API does not expose demo payment settlement:
only the authenticated web simulator can confirm payment.

`422`: validation or inventory conflict; `401`: unauthenticated;
`403`: unauthorized; `404`: unknown flight/booking. Pagination uses
standard Laravel JSON structures.

No real airline integration or monetary payment exists.
