# API Patients

[It's a basic CRUD for patients]

## Packages:
```
* Sanitizer: https://github.com/elegantweb/sanitizer  
"Sanitization library for PHP and the Laravel framework."

* Ramsey UUID: https://github.com/ramsey/uuid  
"A PHP library for generating and working with universally unique identifiers (UUIDs).."  
```
<br>

### Get Address From Zipcode
```
[Retrieves the address for a given zipcode.]
```
#### Request
```
* URL: http://localhost:8082/api/zipcode?zipcode=value
* Method: GET
* Headers:
    * Accept: application/json
    * Content-Type: application/json
```
#### Response
```
[The response will contain the address information for the given zipcode.]
```
#### Example
```
Request
GET | http://localhost:8082/api/zipcode?zipcode=83065060 

Response: 
{
	"status": true,
	"data": {
		"cep": "83065-060",
		"logradouro": "Rua Vítor Gomes de Lima",
		"complemento": "",
		"bairro": "Iná",
		"localidade": "São José dos Pinhais",
		"uf": "PR",
		"ibge": "4125506",
		"gia": "",
		"ddd": "41",
		"siafi": "7885"
	}
}
```

<br>


### Patient List
```
[Retrieves a list from patients with pagination.]
```
#### Request
```
* URL: http://localhost:8082/api/patient
* Params: [length, sort_by, order_by, search]
* Method: GET
* Headers:
    * Accept: application/json
    * Content-Type: application/json
```
#### Response
```
[The response will contain patients.]
```
#### Example
```
Request
GET | http://localhost:8082/api/patient?search=Lang

Response: 
{
	"status": true,
	"data": {
		"current_page": 1,
		"data": [
			{
				"id": "98c88854-0504-4457-b64a-82115fde8021",
				"name": "Dr. Delphia Lang",
				"birth_date": "1981-09-25 00:00:00",
				"cpf": "64248345260",
				"cns": "86341171370",
				"mother_name": "Margaret Macejkovic II",
				"picture": "https:\/\/via.placeholder.com\/640x480.png\/005555?text=in",
				"created_at": "2023-03-26T22:41:20.000000Z",
				"updated_at": "2023-03-26T22:41:20.000000Z",
				"address": {
					"id": "98c88854-5a40-4707-a7d8-4579e435d20f",
					"zipcode": "86384251",
					"address": "388 Elton Courts Suite 259\nNorth Martine, FL 10884",
					"number": "23728",
					"complement": "Suite 455",
					"neighborhood": "New Antonette",
					"city": "West Heidibury",
					"state": "NJ",
					"patient_id": "98c88854-0504-4457-b64a-82115fde8021",
					"created_at": "2023-03-26T22:41:21.000000Z",
					"updated_at": "2023-03-26T22:41:21.000000Z"
				}
			}
		],
		"first_page_url": "http:\/\/localhost:8082\/api\/patient?page=1",
		"from": 1,
		"last_page": 1,
		"last_page_url": "http:\/\/localhost:8082\/api\/patient?page=1",
		"links": [
			{
				"url": null,
				"label": "&laquo; Previous",
				"active": false
			},
			{
				"url": "http:\/\/localhost:8082\/api\/patient?page=1",
				"label": "1",
				"active": true
			},
			{
				"url": null,
				"label": "Next &raquo;",
				"active": false
			}
		],
		"next_page_url": null,
		"path": "http:\/\/localhost:8082\/api\/patient",
		"per_page": 15,
		"prev_page_url": null,
		"to": 1,
		"total": 1
	}
}
```
<br>


### Patient Show
```
[Retrieves a specific patient.]
```
#### Request
```
* URL: http://localhost:8082/api/patient/:id
* Method: GET
* Headers:
    * Accept: application/json
    * Content-Type: application/json
```
#### Response
```
[The response will contain one patient.]
```
#### Example
```
Request
GET | http://localhost:8082/api/patient/98c88854-0504-4457-b64a-82115fde8021

Response: 
{
	"status": true,
	"data": {
		"id": "98c88e6a-2d7e-4a98-805b-3e7c2d30790b",
		"name": "Trystan Langosh",
		"birth_date": "1998-05-26 00:00:00",
		"cpf": "88043185765",
		"cns": "33664583551",
		"mother_name": "Quinn Barrows",
		"picture": "https:\/\/via.placeholder.com\/640x480.png\/0022ff?text=debitis",
		"created_at": "2023-03-26T22:58:22.000000Z",
		"updated_at": "2023-03-26T22:58:22.000000Z",
		"address": {
			"id": "98c88e6a-8bd7-4e75-9c3a-3751003a7750",
			"zipcode": "12370705",
			"address": "8489 Kub Forks\nEast Daphney, VT 75742-6487",
			"number": "3021",
			"complement": "Apt. 580",
			"neighborhood": "Hackettton",
			"city": "Filibertoville",
			"state": "DC",
			"patient_id": "98c88e6a-2d7e-4a98-805b-3e7c2d30790b",
			"created_at": "2023-03-26T22:58:22.000000Z",
			"updated_at": "2023-03-26T22:58:22.000000Z"
		}
	}
}
```
<br>


### Patient Delete
```
[Delete a specific patient.]
```
#### Request
```
* URL: http://localhost:8082/api/patient/:id
* Method: DELETE
* Headers:
    * Accept: application/json
    * Content-Type: application/json
```
#### Response
```
[The response will delete one patient.]
```
#### Example
```
Request
DELETE | http://localhost:8082/api/patient/98c88854-0504-4457-b64a-82115fde8021

Response: 
{
	"status": true,
	"message": "Patient removed."
}
```

<br>


### Patient Store
```
[Create a patient.]
```
#### Request
```
* URL: http://localhost:8082/api/patient
* Method: POST
* Headers:
    * Accept: application/json
    * Content-Type: application/json
* Body: 
{
	"name": "Ringo Starr",
	"birth_date": "07-07-1940",
	"cpf": "38610584054",
	"cns": "238115035490008",
	"mother_name": "Elsie Starkey",
	"zipcode": "83065-060",
	"address": "Test Street",
	"number": "456a",
	"complement": "2nd house",
	"neighborhood": "village",
	"city": "Curitiba",
	"state": "PR"
}
```
#### Response
```
[The response will create one patient.]
```
#### Example
```
Request
POST | http://localhost:8082/api/patient

Body:
{
	"name": "Ringo Starr",
	"birth_date": "07-07-1940",
	"cpf": "38610584054",
	"cns": "238115035490008",
	"mother_name": "Elsie Starkey",
	"zipcode": "83065-060",
	"address": "Test Street",
	"number": "456a",
	"complement": "2nd house",
	"neighborhood": "village",
	"city": "Curitiba",
	"state": "PR"
}

Response: 
{
	"status": true,
	"message": "Patient created with success.",
	"data": {
		"id": "98c88e6d-9092-44ae-bf85-520208b86660",
		"name": "Ringo Starr",
		"birth_date": "1940-07-07 00:00:00",
		"cpf": "38610584054",
		"cns": "238115035490008",
		"mother_name": "Elsie Starkey",
		"picture": null,
		"created_at": "2023-03-26T22:58:24.000000Z",
		"updated_at": "2023-03-26T22:58:24.000000Z",
		"address": {
			"id": "98c88e6d-9424-4d01-a02c-0a856b1cc64d",
			"zipcode": "83065060",
			"address": "Test Street",
			"number": "456a",
			"complement": "2nd house",
			"neighborhood": "village",
			"city": "Curitiba",
			"state": "PR",
			"patient_id": "98c88e6d-9092-44ae-bf85-520208b86660",
			"created_at": "2023-03-26T22:58:24.000000Z",
			"updated_at": "2023-03-26T22:58:24.000000Z"
		}
	}
}
```


### Patient Update
```
[Update a specific patient.]
```
#### Request
```
* URL: http://localhost:8082/api/patient/:id
* Method: PUT
* Headers:
    * Accept: application/json
    * Content-Type: application/json
* Body: 
    Any parameter from store that you want to update.
```
#### Response
```
[The response will update one patient.]
```
#### Example
```
Request
POST | http://localhost:8082/api/patient

Body:
{
	"name": "Ringo Starr - The best beatle",
	"address": "abbey road, 21"
}

Response: 
{
	"status": true,
	"message": "Patient updated with success.",
	"data": {
		"id": "98c83dc1-74dc-4523-8dd6-1b8199d217bf",
		"name": "Ringo Starr - The best beatle",
		"birth_date": "1940-07-07 00:00:00",
		"cpf": "38610584054",
		"cns": "229661101960006",
		"mother_name": "Elsie Starkey",
		"picture": null,
		"created_at": "2023-03-26T19:12:49.000000Z",
		"updated_at": "2023-03-26T19:15:10.000000Z",
		"address": {
			"id": "98c83dc1-78aa-4e4f-afcc-167d20a7c7c0",
			"zipcode": "83065060",
			"address": "abbey road, 21",
			"number": "456a",
			"complement": "2nd house",
			"neighborhood": "village",
			"city": "Curitiba",
			"state": "PR",
			"patient_id": "98c83dc1-74dc-4523-8dd6-1b8199d217bf",
			"created_at": "2023-03-26T19:12:49.000000Z",
			"updated_at": "2023-03-26T19:15:46.000000Z"
		}
	}
}
```
