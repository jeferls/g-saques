# G-Saques API

## API Security

All API requests require an `X-API-Key` header. The value must match the `API_ACCESS_KEY` configured in your environment.

Example request:

```bash
curl -H "X-API-Key: <your-key>" http://localhost/api/health
```
