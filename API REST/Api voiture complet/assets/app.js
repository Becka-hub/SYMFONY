import React from 'react';
import ReactDOM from 'react-dom';
import Hello from './Hello';

const root=document.getElementById('root');

ReactDOM.render(
    <Hello donner={JSON.parse(root.dataset.commentaire)} />,
    root
);
