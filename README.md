# EASE-JWT-Gateway

An authentication gateway which issues JWT for University of Edinburgh logins ([EASE](https://www.ease.ed.ac.uk/)).

When prototyping, feel free to use my hosted version (with no guarantees of uptime) at https://ease.homepages.inf.ed.ac.uk/s1429087/ease-jwt-gateway.

## Getting Started

Replace `s1234567` with your own matriculation username.

1. SSH into the informatics cluster (e.g. `ssh s1234567@student.ssh.inf.ed.ac.uk`).
1. `cd /public/homepages/s1234567/web`
1. `git clone git@github.com:GregBrimble/ease-jwt-gateway.git`
1. `cd ease-jwt-gateway`
1. `sh setup.sh`

## Setup

The following happens automatically as a part of the [`setup.sh`](./setup.sh) script:

- Set permissions for the folders and included [`.htaccess`](./htaccess) file.
- Generate RSA certificate pair for JWT signing.

## Usage

Assuming you followed the instructions above exactly, the service will be available at `https://ease.homepages.inf.ed.ac.uk/s1234567/ease-jwt-gateway`. If you put the code elsewhere, the `web` folder which is exposed at `https://ease.homepages.inf.ed.ac.uk/s1234567`. Just suffix that with your different directory path.

### `GET /`

| Query Parameter | Notes                                                                |
| --------------- | -------------------------------------------------------------------- |
| `redirect_url`  | The URL to redirect the user to, after completing their log in flow. |

GET-ing the `/` path will redirect the user to EASE to login, and then the specified `redirect_url`. The `redirect_url` should be capable of accepting an `application/x-www-form-urlencoded` POST request with a body that looks like:

```ini
jwt=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvd3d3LmVhc2UuZWQuYWMudWtcLyIsInN1YiI6InMxNDI5MDg3IiwiaWF0IjoxNjEyMzcyMjMxLCJuYmYiOjE2MTIzNzIyMzEsImV4cCI6MTYxMjQwODIzMX0.vMpGRHhWfwuz7X__PsJ9H9oqBwmua6ZSrCaR4i5Eep8
```

An example URL would be: [`https://ease.homepages.inf.ed.ac.uk/s1429087/ease-jwt-gateway/?redirect_url=https://httpbin.org/post`](https://ease.homepages.inf.ed.ac.uk/s1429087/ease-jwt-gateway/?redirect_url=https://httpbin.org/post)

### `GET /keys`

To verify the JWT you receive after from the redirect callback, you should use a library capable of decoding RS256 JWTs.

GET-ing the `/keys` path returns a [JSON Web Key Set (JWK Set)](https://tools.ietf.org/html/draft-ietf-jose-json-web-key-41#section-5) to use for validating that this gateway service did in fact author the JWT. These keys are liable to change at any time, so you should fetch the JWT Set from this URL for every single JWT you verify.

### Example

[WIP](https://github.com/symptomizer/frontend/blob/master/api/auth/jwt.ts)

<!--

As an example, using the `nose-jose` package in TypeScript:

```typescript
import { JWK, JWS } from "node-jose";

const KEYS_URL =
  "https://ease.homepages.inf.ed.ac.uk/s1429087/ease-jwt-gateway/keys";

type JWTPayload = {
  iss: string;
  sub: string;
  iat: number;
  nbf: number;
  exp: number;
};

type;

const verifyJWT = async (jwt: string): Promise<JWTPayload> => {
  const keysResponse = await fetch(KEYS_URL);
  const keys = await keysResponse.json();
  const keystore = await JWK.asKeyStore(keys);

  const verifier = JWS.createVerify(keystore, {
    handlers: {},
  });
  return;
};

(async () => {
  const jwtPayload = await verifyJWT(
    "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvd3d3LmVhc2UuZWQuYWMudWtcLyIsInN1YiI6InMxNDI5MDg3IiwiaWF0IjoxNjEyMzcyMjMxLCJuYmYiOjE2MTIzNzIyMzEsImV4cCI6MTYxMjQwODIzMX0.vMpGRHhWfwuz7X__PsJ9H9oqBwmua6ZSrCaR4i5Eep8"
  );

  console.log(jwtPayload); // `false` if invalid, a payload otherwise
})();
```

-->
