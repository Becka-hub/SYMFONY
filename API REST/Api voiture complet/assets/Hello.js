import React from 'react';

const Hello=(props)=>{
    return(
      <div>
        <h1>Bonjour</h1>
        {props.donner.nom}
      </div>
    );
}
export default Hello;