declare global {
    interface Window {
        pjutils: {
            Form: any;
            notify: any;
            Modal: any;
            getData: any;
            doAction: any;
        };
    }
}

export {};
