
<script src="https://cdn.withpersona.com/dist/persona-v5.1.2.js" integrity="sha384-nuMfOsYXMwp5L13VJicJkSs8tObai/UtHEOg3f7tQuFWU5j6LAewJbjbF5ZkfoDo" crossorigin="anonymous"></script>
<script>
  const client = new Persona.Client({
    templateId: 'itmpl_NLsvtnLRfkHXR73vRNr3rm7VqCvG',
    environmentId: 'env_KwYdwC3psgMdtUFXQFeTfs3QgJ5i',
    onReady: () => client.open(),
    onComplete: ({ inquiryId, status, fields }) => {
      console.log(`Completed inquiry ${inquiryId} with status ${status}`);
      window.close();
    }
  });
</script>





