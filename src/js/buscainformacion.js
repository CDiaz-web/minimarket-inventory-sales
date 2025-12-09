



(async () => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();

  await page.goto('https://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias', {
    waitUntil: 'networkidle2',
  });

  // Completa el formulario de búsqueda
  await page.type('#txtRuc', '20100070970'); // Reemplaza con el RUC deseado
  await page.click('#btnAceptar');

  // Espera que cargue la información del RUC
  await page.waitForSelector('#tdDescripcion');

  // Extrae la información
  const result = await page.evaluate(() => {
    const data = {};
    data.razonSocial = document.querySelector('#tdDescripcion').innerText;
    // Extrae más información según sea necesario
    return data;
  });

  console.log('Información del RUC:', result);

  await browser.close();
})();
