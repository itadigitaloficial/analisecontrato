# analisecontrato

## Endpoint analisado

- **Método:** `GET`
- **URL:** `https://n8n.itadigital.com.br/webhook/ava-fornecedor`
- **Status observado:** `200 OK`
- **Formato de resposta:** `application/json` (array de objetos)

## Estrutura retornada

O endpoint retorna uma lista de fornecedores. Cada item possui campos variáveis, mas os principais observados foram:

- Identificação: `_id`, `ID`, `cnpj`
- Dados cadastrais: `nome_razao_social`, `nome_fantasia`, `natureza_juridica`, `data_cadastro`
- Contatos: `email`, `telefone`, `contato_nome`, `contato_telefone`
- Endereço: `endereco`, `endereco_numero`, `endereco_complemento`, `endereco_bairro`, `cidade`, `endereco_uf`, `endereco_cep`, `pais`
- Metadados: `Created Date`, `Modified Date`, `Created By`, `empresa`, `cadastrado_por`, `normalize`
- Dados bancários (flags): `banco_conta_corrente`, `banco_poupanca`

## Exemplo resumido de item

```json
{
  "_id": "1676647870467x290067674894696450",
  "ID": "15518",
  "cnpj": "03.220.438/0001-73",
  "nome_razao_social": "EQUATORIAL ENERGIA S/A",
  "nome_fantasia": "Equatorial",
  "cidade": "São Luís",
  "endereco_uf": "MA",
  "email": "EQ@HOTMAIL.COM",
  "telefone": "(99) 9 9999-9999",
  "banco_conta_corrente": false,
  "banco_poupanca": false,
  "Created Date": "2023-02-17T15:31:13.175Z",
  "Modified Date": "2025-09-15T16:40:15.421Z"
}
```

## Observações

- Alguns campos aparecem em apenas parte dos registros (schema heterogêneo).
- Há registros com diferenças de formatação em `cnpj` e telefones.
- O volume retornado é alto; para consumo em sistemas, vale considerar paginação no fluxo de origem ou pós-processamento.
